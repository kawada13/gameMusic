<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// リクエスト
use App\Http\Requests\PurchaseRecordRequest;

// モデル
use App\User;
use App\Audio;
use App\AudioInstrument;
use App\AudioUnderstanding;
use App\AudioUse;
use App\PurchaseRecord;
use App\ChatMessage;
use App\Announcement;

// イベント
use App\Events\ChatPusher;
use App\Events\ChatRegistered;

class PurchaseRecordController extends Controller
{

    // 購入データを保存
    public function purchase($id)
    {

            $audio = Audio::find($id);

            $PurchaseRecord = new PurchaseRecord;
            $PurchaseRecord->user_id = Auth::id();
            $PurchaseRecord->audio_id = $id;
            $PurchaseRecord->stripe_id = 1;
            $PurchaseRecord->price = $audio->price;
            $PurchaseRecord->save();

            // お知らせテーブルに登録
            $announcement = new Announcement();
            $announcement->user_id = $audio->user->id;
            $announcement->from_user_id = Auth::id();
            $announcement->title = $audio->title . 'が購入されました。';
            $announcement->type = 3;
            $announcement->to_audio = $id;
            $announcement->save();

            $chat = ChatMessage::find(1);

            if($chat == null) {
                $chat = new ChatMessage;
                $chat->chat_room_id = 1;
                $chat->user_id = 2;
                $chat->message = 'テスト';
                $chat->save();
            }

            event(new ChatRegistered($chat));


            // return redirect('http://game-music.fun/mypage/user/purchase_history');
            return redirect('http://localhost/mypage/user/purchase_history');

    }
    


    // ストライプチェックアウト
    public function checkout($id) {

        // そもそもログインしてなかったらアウト

        if (Auth::check()) {


            $audio = Audio::find($id);

            // 自身の作品だったらアウト
            if($audio->user_id == Auth::id()) {
                // return redirect('http://game-music.fun/audios/'. $id);
                return redirect('http://localhost/audios/'. $id);
            }

            // 重複購入アウト
            $is_purchased = $audio->purchase_users()
                                    ->where('user_id', Auth::id())
                                    ->exists();
            if($is_purchased) {
                // return redirect('http://game-music.fun/audios/'. $id);
                return redirect('http://localhost/audios/'. $id);
            }

            $item = [
                'name'        => $audio->title,
                'amount'      => $audio->price,
                'quantity'    => 1,
                'currency'    => 'jpy',
            ];

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => [$item],
                // 'success_url'          => 'http://game-music.fun/' . $id . '/purchase',
                'success_url'          => 'http://localhost/' . $id . '/purchase',
                // 'cancel_url'           => 'http://game-music.fun/audios/'. $id,
                'cancel_url'           => 'http://localhost/audios/'. $id,
            ]);

            return view('cart.checkout', [
                'session' => $session,
                'publicKey' => env('STRIPE_KEY')
            ]);

          } else {

            return redirect('http://game-music.fun/login');
          }


    }


    // あるオーディオを購入済かどうかチェック
    public function isPurchase($id) {

        try{
            $audio = Audio::find($id);

            $is_purchase = $audio->purchase_users()
                                    ->where('user_id', Auth::id())
                                    ->exists();
            return response()->json([
                'message' => '成功',
                'is_purchase' => $is_purchase,
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // ログインユーザーの購入オーディオ一覧
    public function index() {

        try{
            $purchases = Audio::with(['user', 'purchaseRecords'])
                                    ->withTrashed() //クリエイターが削除したものも含む
                                    ->WhereHas('purchase_users', function($q)  {
                                        $q->whereIn('purchase_records.user_id', [Auth::id()]);
                                    })
                                    ->get();

            return response()->json([
                'message' => '成功',
                'purchases' => $purchases,
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // ログインユーザーが出品した商品のうち、購入されたデータ一覧を取得
    public function sales() {

        try{
            $sales_records = PurchaseRecord::with(['user', 'audio'])
                                    ->WhereHas('audio', function($q)  {
                                        $q->whereIn('user_id', [Auth::id()]);
                                    })
                                    ->get();

            return response()->json([
                'message' => '成功',
                'sales_records' => $sales_records,
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // 全てのデータ
    public function allDatas() {

        try{
            $records = PurchaseRecord::with(['audio'=> function($query){
                        $query->with('user');
                    }])->get();

            return response()->json([
                'message' => '成功',
                'records' => $records,
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // 振込申請ページ用で使うオーディオデータ取得
    public function payoutAudio($id) {

        try{
            $audio = Audio::find($id);

            // ログインユーザー自身のオーディオじゃなければ、振込申請が行われては困る(url直打ち対策)⇨これに引っ掛かればリダイレクトreplace
            if(Auth::id() !== $audio->user_id){
                return response()->json([
                    'message' => '不正なアクセスです。',
                ],500);
            }

            return response()->json([
                'message' => '成功',
                'audio' => $audio,
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // 振込申請
    public function payout($id) {

        DB::beginTransaction();

        try{
            $today = date("Y-m-d H:i:s");


            $purchase_record = PurchaseRecord::where('audio_id', $id)
                                               ->first();
            $purchase_record->status = 1;
            $purchase_record->withdraw_at = $today;
            $purchase_record->save();

            DB::commit();

            return response()->json([
                'message' => '成功',
            ],200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }
    // 管理者が入金する
    public function adminPayment($id) {

        DB::beginTransaction();

        try{

            // 管理者しか実行できないように制限
            $user = Auth::user();
            if($user->scope !== 1) {
                return response()->json([
                    'message' => '管理者ユーザーのみ実行できるメソッドです。',
                ],500);
            }

            $purchase_record = PurchaseRecord::find($id);
            $purchase_record->status = 2;
            $purchase_record->save();

            DB::commit();

            return response()->json([
                'message' => '成功',
            ],200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }

}
