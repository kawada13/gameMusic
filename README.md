

## Game Music
### 概要


Game Musicはでゲームの様々なシーンにおける「BGM」、「SE」、「ジングル」、「声」を必要とするユーザーに、その音源を届けるサービスです。  

![スクリーンショット 2021-07-05 4 53 50](https://user-images.githubusercontent.com/61454264/124397795-ff92dd00-dd4c-11eb-9673-ece2758fded5.png)


## 開発のきっかけ

### このようなサービスを必要としている人が周りにいたから 


私の周りには楽曲を制作できる方がいます。
一方で、サウンド特にゲームサウンドを気軽に入手したいという知人もまた、私の周りにはいます。  
単純に、ゲームが好きで様々なサウンドを楽しみたい、という方もいれば、ゲーム制作をしているが、肝心のゲームサウンドに特化してサウンドを気軽に手に入れる場がなかなかないという方もいます。

せっかく良い楽曲を作る能力があっても、必ずしもそれを世の中の方に披露できる場があるとは限りません。  
世の中に自身の作品を聞いてもらいたい、その反応を見たいというクリエイターの想い、  
また、自分が思うゲームサウンドを気軽に入手したい、という想い、  
この双方の想いを是非実現してみたい、という私の考えが、今回の作品を作るきっかけになりました。

## 機能一覧　　

### SPA構成(axios)  

技術的構成は、フロントはVue.js、バックエンドはLaravelをAPIとして機能させることで、SPA構成になっております。  
<img width="700" alt="スクリーンショット 2021-07-04 0 04 00" src="https://user-images.githubusercontent.com/61454264/124358457-59b37580-dc5b-11eb-8062-dbf2fd51ecc1.png">

### 登録/ログイン関連
・ユーザーログイン/登録  
・ゲストユーザーログイン(簡単ログイン)  
・管理者ログイン(mail: admin@gmail.com, password: adminuser)

### 一般ユーザー関連
・プロフィール編集  
　⇨プロフィール画像アップロードはAWS S３へ 
 
・ゲームサウンド購入&購入履歴確認  
　⇨Stripeを使った決済。購入ページにて自身のカード情報を入力し、単発決済。  
 選んだ商品はStripeの決済フォームからクレジットカード情報を入力して、購入することができます。  
 
 <img src="https://user-images.githubusercontent.com/61454264/125234353-83376580-e31b-11eb-999a-8b9f97afe812.gif" width="700">  
 
 
 
・お気に入り登録&お気に入り一覧確認  
　⇨ゲームサウンドのお気に入り  
 
・フォロー&フォロー一覧確認  
　⇨ユーザーをフォローする  
 
・メッセージやり取り(リアルタイムチャット)  
　⇨ユーザー個人間で、メッセージのやりとりができます。音源募集をした後とかに詳しい内容のやりとり等で使われる想定です。 
 
・お知らせ機能  
　⇨チャットメッセージ、お気に入り、フォローが届いたらお知らせから確認できます。  
 <img width="700" alt="スクリーンショット 2021-07-12 14 07 55" src="https://user-images.githubusercontent.com/61454264/125233933-9269e380-e31a-11eb-994a-f6ac6698b1e9.png">

 
 <img src="https://user-images.githubusercontent.com/61454264/123975319-3210a300-d9f8-11eb-9269-b653462c3d17.gif" width="700">  
 

 
 ・募集作成&編集&削除&自身の募集一覧確認  
 　⇨ここでいう「募集」というのは、ゲーム音源を探して自分の欲するものがなかった場合に、自分が期待するサウンドを作ってくれ  
　　るように音源を募集することを指します。  

・オーディオ出品(作成)&編集&削除&一覧確認(ffmpeg)  
　⇨オーディオファイルはAWS S3へ。FFMPEGを使用して音源を加工も含みます。  
　⇨投稿する際は、関連するタグを選択して、投稿する
 
 <img src="https://user-images.githubusercontent.com/61454264/123949722-d89b7a80-d9dd-11eb-9a05-cbf07f8673fb.gif" width="700">
 
 <img src="https://user-images.githubusercontent.com/61454264/123950819-1220b580-d9df-11eb-9a25-f44fd0947218.gif" width="700">
 
 

 
・売上管理  
　⇨売上金の確認、引き出し申請等ができます。引き出し申請後は、管理者ページにて管理者が処理。  
 
 
・振込口座設定  
　⇨売上金引き落としのための、口座設定機能です。(編集も可能)  
 
 
### 管理者ユーザー関連  

・出金管理  
　⇨売上金引き落とし申請中のユーザー一覧を確認、入金処理後、出金済一覧へ。  
 
 <img src="https://user-images.githubusercontent.com/61454264/123998701-b4a35d80-da0c-11eb-9f29-97c79d8aee4f.gif" width="700">

 
・登録ユーザー管理  

・登録オーディオ管理  


### その他機能  

・検索  
　⇨フリーワード、ジャンル選択でオーディオ、募集を検索できます。  
 
 ■フリーワード検索(トップから)  
 <img src="https://user-images.githubusercontent.com/61454264/124397908-dcb4f880-dd4d-11eb-8742-3477da0ee1f9.gif" width="700">  
 

 ■タグ検索(トップから)  
 <img src="https://user-images.githubusercontent.com/61454264/124397945-171e9580-dd4e-11eb-875c-11f6e887f302.gif" width="700">  
 
  
 
 ■オーディオ詳細画面からの検索  
 <img src="https://user-images.githubusercontent.com/61454264/124398030-82686780-dd4e-11eb-81dc-3ae5ef450cea.gif" width="700"> 
 

 
・ページネーション  
　⇨オーディオ一覧、募集一覧等のページで実装。  
 
・レスポンシブ対応(bootstrap)  
　⇨Bootstrapを使用して対応。  
 
・PHPUnit  
<img width="500" alt="スクリーンショット 2021-07-01 1 03 24" src="https://user-images.githubusercontent.com/61454264/123995465-54f78300-da09-11eb-8ad2-e198d8cff4fb.png">


・フラッシュメッセージ表示(vue-toasted)  
　⇨ログイン、ログアウト、登録、編集、削除時にメッセージ表示  


## 使用技術   
### フロントエンド  
　・Vue.js 2.5.17  
　・vue-router 3.5.1  
　・vuex 3.6.2  
　・vuejs-paginate 2.1.0  
　・vue-toasted 1.1.28   
　・axios 0.19  
　・bootstrap 4.0.0  
　・HTML/CSS

### バックエンド  
　・PHP 7.2.5  
　・Laravel 7.30.4  
　・laravel/sanctum 2.11  
　・stripe-php 7.82  
　・PHPUnit 8.5.8  
 
### インフラ  
　・Docker 20.10.05  
　・docker-compose 1.29.0  
　・CircleCi(テスト、デプロイ自動化)  
　・AWS(EC2,EIP,RDS,VPC,S3,IAM,Route53)  
　・nginx 1.18  
　・mysql 5.7  
 
## DB設計関連  
### ER図
![スクリーンショット 2021-06-27 15 54 23](https://user-images.githubusercontent.com/61454264/123541773-2ff0df00-d781-11eb-92d1-5b626507e5c4.png)  

### テーブル
| テーブル名 | 内容 |
| ------------- | ------------- |
| audios  | オーディオ情報  |
| audio_instrument  | オーディオ機材の中間テーブル  |
| audio_understanding  | オーディオイメージの中間テーブル  |
| audio_use  | オーディオ用途の中間テーブル  |
| announcements  | 通知、お知らせ情報テーブル  |
| instruments  | 機材情報マスター  |
| understandings  | イメージ情報マスター  |
| uses  | 用途情報マスター  |
| chat_messages  | チャットメッセージ情報  |
| chat_rooms  | チャットルーム情報  |
| chat_rooms_users  | ユーザーのチャットルーム情報  |
| favorites  | お気に入り中間テーブル  |
| transfer_accounts  | ユーザー口座情報  |
| transfer_records  | 振込申請履歴  |
| users  | ユーザー情報  |
| user_informations  | ユーザープロフィール情報  |
| user_follows  | ユーザーフォロー情報  |  


### AWS構成図
<img width="821" alt="スクリーンショット 2021-07-18 17 08 08" src="https://user-images.githubusercontent.com/61454264/126060349-cd07a8f4-76dc-4ad3-a034-42f626713140.png">





