
## Game Music
### 概要


Game Musicはでゲームの様々なシーンにおける「BGM」、「SE」、「ジングル」、「声」を必要とするユーザーに、その音源を届けるサービスです。  

<img width="1436" alt="スクリーンショット 2021-06-30 19 47 21" src="https://user-images.githubusercontent.com/61454264/123948036-fec01b00-d9db-11eb-8042-6955c3209f67.png">


## 開発のきっかけ

### このようなサービスを必要としている人が周りにいたから 


私の周りには楽曲を制作できる方がいます。
一方で、サウンド特にゲームサウンドを気軽に入手したいという知人もまた、私の周りにはいます。  
単純に、ゲームが好きで様々なサウンドを楽しみたい、という方もいれば、ゲーム制作をしているが、肝心のゲームサウンドに特化して  
サウンドを気軽に手に入れる場がなかなかないという方もいます。

せっかく良い楽曲を作る能力があっても、必ずしもそれを世の中の方に披露できる場があるとは限りません。  
世の中に自身の作品を聞いてもらいたい、その反応を見たいというクリエイターの想い、  
また、自分が思うゲームサウンドを気軽に入手したい、という想い、  
この双方の想いを是非実現してみたい、という私の考えが、今回の作品を作るきっかけになりました。

## 機能一覧　　

### 完全SPA構成  

技術的構成は、フロントはVue.js、バックエンドはLaravelをAPIとして機能させることで、完全SPA構成になっております。

### 登録/ログイン関連(laravel/sanctum)
・ユーザーログイン/登録  
・ゲストユーザーログイン(簡単ログイン)  
・管理者ログイン(mail: admin@gmail.com, password: adminuser)

### 一般ユーザー関連
・プロフィール編集  
　⇨プロフィール画像アップロードはAWS S３へ 
 
・ゲームサウンド購入&購入履歴確認  
　⇨Stripeを使った決済。購入ページにて自身のカード情報を入力し、単発決済。  
 
 <img src="https://user-images.githubusercontent.com/61454264/123968660-6719f700-d9f2-11eb-924e-2f46d2e28f61.gif" width="700">  
 
 選んだ商品はStripeの決済フォームからクレジットカード情報を入力して、購入することができます。  
 
 <img src="https://user-images.githubusercontent.com/61454264/123971382-f2948780-d9f4-11eb-85d9-a92e2f6f9bc8.gif" width="700">  
 
 
 
・お気に入り登録&お気に入り一覧確認  
　⇨ゲームサウンドのお気に入り  
 
・フォロー&フォロー一覧確認  
　⇨ユーザーをフォローする  
 
・メッセージやり取り  
　⇨ユーザー個人間で、メッセージのやりとりができます。音源募集をした後とかに詳しい内容のやりとり等で使われる想定です。  
 
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
 <img src="https://user-images.githubusercontent.com/61454264/123986810-9b48e400-da01-11eb-8b17-43ad1e63a2ec.gif" width="700">  
 
 ■タグ検索(トップから)  
 <img src="https://user-images.githubusercontent.com/61454264/123989609-04315b80-da04-11eb-8a98-c653d896887c.gif" width="700">  
 
 ■オーディオ詳細画面からの検索  
 <img src="https://user-images.githubusercontent.com/61454264/123990898-2c6d8a00-da05-11eb-8677-1ac3c05ab2dc.gif" width="700">  
 
・ページネーション  
　⇨オーディオ一覧、募集一覧等のページで実装。  
 
・レスポンシブ対応(bootstrap)  
　⇨Bootstrapを使用して対応。  
 
・PHPUnit  
<img width="342" alt="スクリーンショット 2021-07-01 1 03 24" src="https://user-images.githubusercontent.com/61454264/123995465-54f78300-da09-11eb-8ad2-e198d8cff4fb.png">


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
　・bootstrap　4.0.0  
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
　・CircleCi  
　・AWS(EC2,RDS,VPC,S3,IAM,)  
　・nginx:1.18  
　・mysql:5.7  
 
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
| instruments  | 機材情報マスター  |
| understandings  | イメージ情報マスター  |
| uses  | 用途情報マスター  |
| chat_messages  | チャットメッセージ情報  |
| chat_rooms  | チャットルーム情報  |
| chat_rooms_users  | ユーザーのチャットルーム情報  |
| favorites  | お気に入り中間テーブル  |
| transfer_accounts  | ユーザー口座情報  |
| users  | ユーザー情報  |
| user_informations  | ユーザープロフィール情報  |
| user_follows  | ユーザーフォロー情報  |



