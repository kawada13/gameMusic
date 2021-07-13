<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

// モデル
use App\User;
use App\Audio;
use App\AudioInstrument;
use App\AudioUnderstanding;
use App\AudioUse;
use App\Announcement;
use App\ChatMessage;

// イベント
use App\Events\ChatPusher;
use App\Events\ChatRegistered;


class UserFollowController extends Controller
{
    // ログインユーザーがあるユーザーをフォローする
    public function store($id)
    {
        try {
            $user = User::find($id);

            // 既にフォローしていたらもう一度フォローすることを拒否する
            $is_follow = $user->followers()
                                    ->where('follow_id', Auth::id())
                                    ->exists();
            if($is_follow) {
                return response()->json([
                    'message' => '既にフォローしてます.',
                ],500);
            }

            // フォローする相手が自分だったらフォローすることを拒否
            if($user->id == Auth::id()) {
                return response()->json([
                    'message' => 'フォロー対象が自身なので拒否',
                ],500);
            }

            $user->followers()->attach(Auth::id());

            // お知らせテーブルに登録
            $announcement = new Announcement();
            $announcement->user_id = $id;
            $announcement->from_user_id = Auth::id();
            $announcement->title = Auth::user()->name . 'にフォローされました。';
            $announcement->type = 2;
            $announcement->save();

            $chat = ChatMessage::find(1);

            event(new ChatRegistered($chat));

            return response()->json([
                'message' => '成功',
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // ログインユーザーがフォローを解除
    public function delete($id)
    {
        try{
            $user = User::find($id);
            $user->followers()->detach(Auth::id());


            // お知らせテーブルから該当データ削除
            // $announcements = Announcement::where('user_id', $id)
            //                                 ->where('type', 2)
            //                                 ->get();

            // foreach($announcements as $announcement){
            //     $announcement->delete();
            // }

            // $chat = ChatMessage::find(1);

            // event(new ChatRegistered($chat));


            return response()->json([
                'message' => '成功',
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // ログインユーザーがあるユーザーをフォロー済かどうかtrue or falseで返す
    public function isFollow($id)
    {
        try{
            $user = User::find($id);

            $is_follow = $user->followers()
                                    ->where('follow_id', Auth::id())
                                    ->exists();
            return response()->json([
                'message' => '成功',
                'is_follow' => $is_follow,
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // ログインユーザーのフォローユーザー一覧
    public function lists()
    {
        try{

            $follow_users = User::with('userInformation')
                                    ->WhereHas('followers', function($q)  {
                                        $q->whereIn('user_follow.follow_id', [Auth::id()]);
                                    })
                                    ->get();


            return response()->json([
                'message' => '成功',
                'follow_users' => $follow_users,
            ],200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
}
