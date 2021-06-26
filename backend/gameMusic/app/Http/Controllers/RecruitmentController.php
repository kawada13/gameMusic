<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// リクエスト
use App\Http\Requests\RecruitmentRequest;

// モデル
use App\User;
use App\Recruitment;

class RecruitmentController extends Controller
{
    // 作成
    public function store(RecruitmentRequest $request) {

        DB::beginTransaction();

        try {
            $recruitment = new Recruitment;
            $recruitment->user_id = Auth::id();
            $recruitment->title = $request->title;
            $recruitment->content = $request->content;
            $recruitment->save();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitment' => $recruitment
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }
    // 編集
    public function update(RecruitmentRequest $request, $id) {



        DB::beginTransaction();

        try {
            $recruitment = Recruitment::find($id);

            // 自身の募集でないと編集できない
            if($recruitment->user_id !== Auth::id()) {
                return response()->json([
                    'message' => '自身の募集ではないので編集できません。',
                ],500);
            }
            $recruitment->title = $request->title;
            $recruitment->content = $request->content;
            $recruitment->save();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitment' => $recruitment
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }

    }
    // 削除
    public function delete($id) {

        DB::beginTransaction();

        try {
            $recruitment = Recruitment::find($id);

            // 自身の募集でないと削除できない
            if($recruitment->user_id !== Auth::id()) {
                return response()->json([
                    'message' => '自身の募集ではないので削除できません。',
                ],500);
            }

            $recruitment->delete();

            DB::commit();
            return response()->json([
                'message' => '成功',
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // 全データを取得
    public function index() {

        DB::beginTransaction();

        try {
            $recruitments = Recruitment::with('user')
                                    ->latest()
                                    ->get();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitments' => $recruitments,
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // ログインユーザーの全データを取得
    public function loginuUserIndex() {

        DB::beginTransaction();

        try {
            $recruitments = Recruitment::with('user')
                                    ->where('user_id', Auth::id())
                                    ->get();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitments' => $recruitments,
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // 詳細
    public function show($id) {

        DB::beginTransaction();

        try {
            $recruitment = Recruitment::with(['user' => function($query){
                                        $query->with('userInformation');
                                    }])
                                    ->where('id', $id)
                                    ->first();
            $authId = Auth::id();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitment' => $recruitment,
                'authId' => $authId,
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }
    // ログインユーザーの特定の募集を取得(更新のため)
    public function edit($id) {

        DB::beginTransaction();

        try {
            $recruitment = Recruitment::with('user')
                                    ->where('user_id', Auth::id())
                                    ->where('id', $id)
                                    ->first();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitment' => $recruitment,
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // 最新データ6件取得(トップページで使うやつ)
    public function topindex() {

        DB::beginTransaction();

        try {
            $recruitments = Recruitment::with('user')
                                    ->latest()
                                    ->take(6)
                                    ->get();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'recruitments' => $recruitments,
            ],200);
        }

        catch (\Exception $e) {
            // データベース巻き戻し
            DB::rollback();

            return response()->json([
                'message' => '失敗',
                'errorInfo' => $e
            ],500);
        }
    }

    // 検索募集取得
    public function searchIndex(Request $request) {

        try {
            $keyword = $request->keyword;

            $_keyword = str_replace('　', ' ', $keyword);  //全角スペースを半角に変換
            $_keyword = preg_replace('/\s(?=\s)/', '', $_keyword); //連続する半角スペースは削除
            $_keyword = trim($_keyword); //文字列の先頭と末尾にあるホワイトスペースを削除

            // キーワード入力があったら
            if($request->keyword) {
                $recruitments = Recruitment::with('user')
                                 ->where('title', 'like', "%$_keyword%")
                                 ->orwhere('content', 'like', "%$_keyword%")
                                 ->get();
            }

            // 何も入力がなかったら
            if(!$request->keyword) {
                $recruitments = Recruitment::with('user')->get();
            }

            return response()->json([
                'message' => '検索成功',
                'recruitments' => $recruitments,
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
