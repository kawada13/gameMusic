<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\TransferRecord;

class TransferRecordController extends Controller
{
    // 申請履歴作成
    public function store(Request $request) {

        DB::beginTransaction();

        try {
            $transferRecord = new TransferRecord;
            $transferRecord->user_id = Auth::id();
            $transferRecord->price = $request->price;
            $transferRecord->save();

            DB::commit();
            return response()->json([
                'message' => '成功',
                'transferRecord' => $transferRecord
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
}
