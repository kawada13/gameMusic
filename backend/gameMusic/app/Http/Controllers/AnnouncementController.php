<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Announcement;

class AnnouncementController extends Controller
{
    // ログインユーザーのお知らせ一覧

    public function index() {

        $announcements = Announcement::select('*')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        return response()->json([
            'message' => '成功',
            'announcements' => $announcements
        ], 200);
    }

}
