<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 認証関連
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register')->name('register');
Route::post('/logout', 'AuthController@logout')->name('logout');

// マスターテーブル関連
Route::get('/sound_type/sound', 'SoundTypeController@sound')->name('type.sound');
Route::get('/sound_type/understanding', 'SoundTypeController@understanding')->name('type.understanding');
Route::get('/sound_type/use', 'SoundTypeController@use')->name('type.use');
Route::get('/sound_type/instrument', 'SoundTypeController@instrument')->name('type.instrument');

// 特定のオーディオ取得
Route::get('/audio/{id}/show', 'AudioController@audioShow')->name('audio.show');
// 特定のユーザーのオーディオ一覧(ユーザー詳細から「もっとみる」を押して進んだページで使うデータ)
Route::get('/user/{id}/audios', 'AudioController@userAudios')->name('user.audio.show');
// 過去に作られた古い順6件のオーディオ情報(トップのオーディオ一覧のところに載せるやつ)
Route::get('/audio/old', 'AudioController@oldAudios')->name('audio.old');
 // 新着順3件のオーディオ情報(新着オーディオのところに載せるやつ)
Route::get('/audio/new', 'AudioController@newAudios')->name('audio.new');
// 検索オーディオ取得
Route::post('/audios', 'AudioController@audioSearchIndex')->name('audio.index');


// 特定のユーザー情報取得
Route::get('/user/{id}/show', 'UserController@show')->name('user.show');


// 募集全件取得
Route::get('recruitments', 'RecruitmentController@index')->name('recruitment.index');
// 最新データ6件取得(トップページで使うやつ)
Route::get('top/recruitments', 'RecruitmentController@topindex')->name('recruitment.topindex');
// 募集詳細取得
Route::get('recruitment/{id}', 'RecruitmentController@show')->name('recruitment.show');
// 検索募集取得
Route::post('/searchIndex', 'RecruitmentController@searchIndex')->name('audio.searchIndex');






Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ユーザー情報取得
    Route::get('/user_information', 'UserController@loginUserInformation')->name('loginUserInformation');
    // ユーザープロフィール編集
    Route::post('/user_information', 'UserController@profileEdit')->name('profileEdit');
    // ユーザープロフィール編集
    Route::get('/users', 'UserController@index')->name('users.index');

    // オーディオ作成
    Route::post('/audio/create', 'AudioController@store')->name('audio.store');
    // ログインユーザーの出品オーディオ一覧
    Route::get('/exhibited_audios', 'AudioController@exhibitedAudios')->name('exhibited_audios');
    // ログインユーザーの特定のオーディオ取得
    Route::get('/exhibited_audio/{id}/show', 'AudioController@exhibitedAudioShow')->name('exhibited_show');
    // ログインユーザーの特定のオーディオアップデート
    Route::post('/exhibited_audio/{id}/update', 'AudioController@exhibitedAudioUpdate')->name('exhibited_update');
    // ログインユーザーの特定のオーディオ削除
    Route::post('/exhibited_audio/{id}/delete', 'AudioController@exhibitedAudioDelete')->name('exhibited_delete');
    // オーディオ全件取得
    Route::get('/audios', 'AudioController@audios')->name('audios');
    // オーディオを一時publicに保存
    Route::post('/audios/download', 'AudioController@download')->name('download');

    // 振り込み口座情報作成
    Route::post('/transferAccount/store', 'TransferAccountController@store')->name('transferAccount.store');
    // 振り込み口座情報アップデート
    Route::post('/transferAccount/{id}/update', 'TransferAccountController@update')->name('transferAccount.update');
    // 振り込み口座情報取得
    Route::get('/transferAccount/show', 'TransferAccountController@show')->name('transferAccount.show');

    // お気に入り登録
    Route::post('audio/{id}/favorite', 'FavoriteController@store')->name('favorite');
    // お気に入り解除
    Route::post('audio/{id}/unfavorite', 'FavoriteController@delete')->name('unfavorite');
    // お気に入り済かどうか確認
    Route::post('audio/{id}/isfavorite', 'FavoriteController@isFavorite')->name('isfavorite');
    // ログインユーザーのお気に入り作品一覧
    Route::get('favoriteLists', 'FavoriteController@lists')->name('favoriteLists');
    // フォロー
    Route::post('user/{id}/follow', 'UserFollowController@store')->name('follow');
    // アンフォロー
    Route::post('user/{id}/unfollow', 'UserFollowController@delete')->name('unfollow');
    // フォロー済かどうか確認
    Route::post('user/{id}/isfollow', 'UserFollowController@isFollow')->name('isfollow');
    // ログインユーザーのフォローユーザー一覧
    Route::get('followLists', 'UserFollowController@lists')->name('followLists');


    // 購入
    // Route::post('{id}/purchase', 'PurchaseRecordController@purchase')->name('purchase');
    // あるオーディオを購入済かどうかチェック
    Route::get('{id}/isPurchase', 'PurchaseRecordController@isPurchase')->name('isPurchase');
    // ログインユーザーの購入オーディオ一覧
    Route::get('purchases', 'PurchaseRecordController@index')->name('purchases');
    // ログインユーザーが出品した商品のうち、購入されたデータ一覧を取得
    Route::get('sales', 'PurchaseRecordController@sales')->name('sales');
    // 全ての購入履歴データ取得
    Route::get('allPurchases', 'PurchaseRecordController@allDatas')->name('allDatas');


    // 振込申請ページ用で使うオーディオデータ取得
    Route::get('audio/{id}/payout', 'PurchaseRecordController@payoutAudio')->name('audio_payout');
    // 振込申請
    Route::post('{id}/payout', 'PurchaseRecordController@payout')->name('payout');
    // 管理者が入金する
    Route::post('{id}/payment', 'PurchaseRecordController@adminPayment')->name('adminPayment');


    // チャットメッセージ作成
    Route::post('message/{id}', 'ChatController@createChat')->name('createChat');
    // ログインユーザーのチャットルーム一覧
    Route::get('chatRooms', 'ChatController@chatRooms')->name('chatRooms');
    // ある相手とのチャット履歴取得
    Route::get('message/{id}', 'ChatController@chatMessages')->name('chatMessages');
    // あるチャットを削除
    Route::delete('message/{id}', 'ChatController@deleteChatMessages')->name('deleteChatMessages');


    // 募集作成
    Route::post('recruitment/store', 'RecruitmentController@store')->name('recruitment.store');
    // 募集アップデート
    Route::post('recruitment/{id}/update', 'RecruitmentController@update')->name('recruitment.update');
    // 募集削除
    Route::delete('recruitment/{id}', 'RecruitmentController@delete')->name('recruitment.delete');
    // ログインユーザーの募集全件取得
    Route::get('loginuser/recruitments', 'RecruitmentController@loginuUserIndex')->name('recruitment.loginuUserIndex');
    // ログインユーザーの特定の募集を取得
    Route::get('recruitment/edit/{id}', 'RecruitmentController@edit')->name('recruitment.edit');


    // ログインユーザーのお知らせ一覧
    Route::get('announcements', 'AnnouncementController@index')->name('announcements');

});
