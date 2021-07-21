<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\TransferRecord;

class TransferRecordController extends Controller
{
    // 申請履歴作成
    public function index() {


        try {
            $transferRecords = TransferRecord::where('user_id', Auth::id())
                                                ->latest()
                                                ->get();

            return response()->json([
                'message' => '成功',
                'transferRecords' => $transferRecords
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
