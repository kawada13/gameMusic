<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


// モデル
use App\ChatRoom;
use App\ChatRoomUser;
use App\ChatMessage;
use App\User;
use App\Announcement;

// リクエスト
use App\Http\Requests\ChatRequest;


// イベント
use App\Events\ChatPusher;
use App\Events\ChatRegistered;

class ChatController extends Controller
{

     // メッセージ作成
    public function createChat(ChatRequest $request, $id){

        // 自分の持っているチャットルームを取得
        $current_user_chat_rooms = ChatRoomUser::where('user_id', Auth::id())
        ->pluck('chat_room_id');

        // 自分の持っているチャットルームからチャット相手のいるルームを探す(るーむid)
        $chat_room_id = ChatRoomUser::whereIn('chat_room_id', $current_user_chat_rooms)
            ->where('user_id', $id)
            ->pluck('chat_room_id');


        // なければ作成する
        if ($chat_room_id->isEmpty()){

            ChatRoom::create(); //チャットルーム作成

            $latest_chat_room = ChatRoom::orderBy('created_at', 'desc')->first(); //最新チャットルームを取得

            $chat_room_id = $latest_chat_room->id;

            // 自身を登録
            ChatRoomUser::create(
            ['chat_room_id' => $chat_room_id,
            'user_id' => Auth::id()]);

            // 相手も登録
            ChatRoomUser::create(
            ['chat_room_id' => $chat_room_id,
            'user_id' => $id]);
        }

        // チャットルーム取得時はオブジェクト型なので数値に変換
        if(is_object($chat_room_id)){
            $chat_room_id = $chat_room_id->first();
        }


        // チャット内容を作成
        $chat = new ChatMessage();
        $chat->chat_room_id = $chat_room_id;
        $chat->user_id = Auth::id();
        $chat->message = $request->message;
        $chat->save();


        // お知らせテーブルに登録
        $announcement = new Announcement();
        $announcement->user_id = $id;
        $announcement->from_user_id = Auth::id();
        $announcement->title = Auth::user()->name . 'さんからメッセージがありました。';
        $announcement->save();

        event(new ChatPusher($chat));


        return response()->json([
            'message' => '成功',
        ], 200);

    }
     // ログインユーザーのチャットルーム一覧
    public function chatRooms(){

        try {

            // 自分の持っているチャットルームを取得
            $chat_room_ids = ChatRoomUser::with(['user' => function($query){
                                        $query->with('userInformation');
                                    }])
                                    ->latest()
                                    ->where('user_id', Auth::id())
                                    ->pluck('chat_room_id');
            

            $chat_rooms = ChatRoomUser::with(['user' => function($query){
                                        $query->with('userInformation');
                                    }])
                                    ->whereIn('chat_room_id', $chat_room_ids)
                                    ->where('user_id', '<>', Auth::id())
                                    ->latest()
                                    ->get();
            
            $chat_message_count = [];

            foreach($chat_rooms as $chat_room){
                $message_count = ChatMessage::where('chat_room_id', $chat_room->chat_room_id)
                                    ->where('user_id', '<>', Auth::id())
                                    ->where('is_read', 0)
                                    ->get()
                                    ->count();

                array_push($chat_message_count,$message_count);
            }


            return response()->json([
                'message' => '成功',
                'chat_rooms' => $chat_rooms,
                'chat_message_count' => $chat_message_count,
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }

     // ある相手とのチャット履歴取得
    public function chatMessages($id){


        try {

            // 自分の持っているチャットルームを取得
            $current_user_chat_rooms = ChatRoomUser::where('user_id', Auth::id())
            ->pluck('chat_room_id');

            // 自分の持っているチャットルームからチャット相手のいるルームを探す(るーむid)
            $chat_room_id = ChatRoomUser::whereIn('chat_room_id', $current_user_chat_rooms)
                ->where('user_id', $id)
                ->pluck('chat_room_id');

            // なければからの配列を返す
            if ($chat_room_id->isEmpty()){
                $chat_messages = [];

                return response()->json([
                    'message' => '成功',
                    'chat_messages' => $chat_messages,
                ], 200);
            } else {
                $chat_messages = ChatMessage::with(['user' => function($query){
                                                $query->with('userInformation');
                                            }])
                                            ->where('chat_room_id', $chat_room_id)
                                            ->latest()
                                            ->get();



                // 上記の$chat_messagesの中から自分のメッセージ以外のチャットを取得
                $to_chat_messages = ChatMessage::with(['user' => function($query){
                                        $query->with('userInformation');
                                    }])
                                    ->where('chat_room_id', $chat_room_id)
                                    ->where('user_id', '<>', Auth::id())
                                    ->get();




                // みたやつを既読にする
                foreach($to_chat_messages as $to_chat_message){
                    $to_chat_message->is_read = 1;
                    $to_chat_message->save();
                 }

                //  アナウンステーブルの方も既読にする
                $announcements = Announcement::where('user_id', Auth::id())
                                               ->where('from_user_id', $id)
                                               ->where('type', 0)
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

                return response()->json([
                    'message' => '成功',
                    'chat_messages' => $chat_messages,
                ], 200);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }
     // あるチャットを削除
    public function deleteChatMessages($id){

        try {

            $chat_message = ChatMessage::find($id);
            $chat_message->delete();

            return response()->json([
                'message' => '成功',
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }
}
