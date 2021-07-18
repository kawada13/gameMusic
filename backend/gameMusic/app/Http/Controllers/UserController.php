<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// イベント
use App\Events\TestAdded;


// リクエスト
use App\Http\Requests\UserInformationRequest;

// モデル
use App\UserInformation;
use App\User;
use App\Audio;
use App\Announcement;
use App\ChatMessage;

// イベント
use App\Events\ChatPusher;
use App\Events\ChatRegistered;

class UserController extends Controller
{
    // ログインユーザーの情報
    public function loginUserInformation()
    {

        try{
            $user = Auth::user();
            $user_information = UserInformation::select('*')
                ->where('user_id', $user->id)
                ->first();

            return response()->json([
                'user' => $user,
                'user_information' => $user_information,
                'message' => '成功'
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }

    // ログインユーザーが自分のプロフィールを編集
    public function profileEdit(UserInformationRequest $request)
    {

        DB::beginTransaction();

        try {
            // ゲストユーザーだったら編集できない
            $user = User::find($request->user_id);

            if($user->scope == 2) {
                return response()->json([
                    'message' => 'ゲストユーザーのため編集できません。'
                ], 500);
            }
            $userInformation = UserInformation::select('*')
                            ->where('user_id', $request->user_id)
                            ->first();

            // 画像アップロードされてたら以下
            if ($request->profile_image) {

                //送られてきた画像を取得
                $image = $request->profile_image;

                // すでに画像変更したことがある場合、既存のS3に入ってる画像を削除
                if($userInformation->profile_image) {
                    Storage::disk('s3')->delete(parse_url($userInformation->profile_image)['path']);
                }

                // 新しくアップロードされた画像をS3にアップロード
                $path = Storage::disk('s3')->put('/images', $image, 'public');

                // カラムにフルパスを代入
                $userInformation->profile_image = Storage::disk('s3')->url($path);
            }

            // その他カラム
            $user->name = $request->name;
            $userInformation->introduce = $request->introduce;
            $userInformation->instrument = $request->instrument;

            // データベース保存
            $user->save();
            $userInformation->save();

            DB::commit();

            return response()->json([
                'message' => '成功'
            ], 200);

        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            // DBに合わせるため、s3ないの画像削除
            Storage::disk('s3')->delete($path);

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // ユーザー情報取得（ユーザーの詳細ページのためのデータを取得する）
    public function show($id) {

        try{
            $user = User::with(['userInformation', 'transfer_account'])
                            ->where('id', $id)
                            ->first();
            $userAudios = Audio::where('user_id', $user->id)
                                ->latest()
                                ->take(2)
                                ->get();


            if(Auth::id() !== $user->id) {


                $announcements = Announcement::where('user_id', Auth::id())
                    ->where('is_read', 0)
                    ->where('type', 2)
                    ->get();

                foreach($announcements as $announcement){
                    $announcement->is_read = 1;
                    $announcement->save();
                }

                $chat = ChatMessage::find(1);

                if($chat == null) {
                    $chat = new ChatMessage;
                    $chat->chat_room_id = 1;
                    $chat->user_id = 2;
                    $chat->message = 'テスト';
                    $chat->save();
                }

                event(new ChatRegistered($chat));

            }

            return response()->json([
                'user' => $user,
                'userAudios' => $userAudios,
                'authId' => Auth::id(),
                'message' => '成功'
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }

    // 全ユーザー情報取得(管理者ユーザーを除く)
    public function index() {

        try{
            $users = User::with('userInformation')
                            ->where('scope', 0)
                            ->get();

            return response()->json([
                'users' => $users,
                'message' => '成功'
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // test)
    public function test() {

        $task = ['id' => 1, 'name' => 'こんちは！'];

        event(new TestAdded('hello world'));

            return response()->json([
                'message' => '成功'
            ],200);
    }
}
