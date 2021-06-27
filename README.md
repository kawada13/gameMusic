## Game Music
### 概要

Game Musicはでゲームの様々なシーンにおける「BGM」、「SE」、「ジングル」、「声」を必要とするユーザーに、その音源を届けるサービスです。  

技術的構成は、フロントはVue.js、バックエンドはLaravelをAPIとして機能させることで、完全SPA構成になっております。


## 開発のきっかけ
大きく３つあります。

### ①周りの知人がこのようなサービスを必要としていたから 


私の周りには楽曲を制作できる方がいます。
一方で、サウンド特にゲームサウンドを気軽に入手したいという知人もまた、私の周りにはいます。  
単純に、ゲームが好きで様々なサウンドを楽しみたい、という方もいれば、ゲーム制作をしているが、肝心のゲームサウンドに特化して  
サウンドを気軽に手に入れる場がなかなかないという方もいます。

せっかく良い楽曲を作る能力があっても、必ずしもそれを世の中の方に披露できる場があるとは限りません。  
世の中に自身の作品を聞いてもらいたい、その反応を見たいというクリエイターの想い、  
また、自分が思うゲームサウンドを気軽に入手したい、という想い、  
この双方の想いを是非実現してみたい、という私の考えが、今回の作品を作るきっかけになりました。

### ②まだ世の中にないサービスを作りたかったから  
考え中。
### ③自身が音楽、ゲームが好きだから  

作るなら自分の趣味に関連するもの作りたいと思いました。


## 機能一覧　　

### 登録/ログイン関連(laravel/sanctum)
・ユーザーログイン/登録  
・ゲストユーザーログイン(簡単ログイン)  
・管理者ログイン(mail: admin@gmail.com, password: adminuser)

### 一般ユーザー関連
・プロフィール編集  
　⇨プロフィール画像アップロードはAWS S３へ 
 
・ゲームサウンド購入&購入履歴確認  
　⇨Stripeを使った決済。購入ページにて自身のカード情報を入力し、単発決済。  
 
・お気に入り登録&お気に入り一覧確認  
　⇨ゲームサウンドのお気に入り  
 
・フォロー&フォロー一覧確認  
　⇨ユーザーをフォローする  
 
・メッセージやり取り  
　⇨ユーザー個人間で、メッセージのやりとりができます。音源募集をした後とかに詳しい内容のやりとり等で使われる想定です。  
 
 ・募集作成&編集&削除&自身の募集一覧確認  
 　⇨ここでいう「募集」というのは、ゲーム音源を探して自分の欲するものがなかった場合に、自分が期待するサウンドを作ってくれ  
　　るように音源を募集することを指します。  

・オーディオ出品(作成)&編集&削除&一覧確認(ffmpeg)  
　⇨オーディオファイルはAWS S3へ。FFMPEGを使用して音源を加工も含みます。  
 
・売上管理  
　⇨売上金の確認、引き出し申請等ができます。引き出し申請後は、管理者ページにて管理者が処理。  
 
・振込口座設定  
　⇨売上金引き落としのための、口座設定機能です。(編集も可能)  
 
 
### 管理者ユーザー関連  

・出金管理  
　⇨売上金引き落とし申請中のユーザー一覧を確認、入金処理後、出金済一覧へ。  
 
・登録ユーザー管理  

・登録オーディオ管理  


### その他機能  

・検索  
　⇨フリーワード、ジャンル選択でオーディオ、募集を検索できます。  
 
・ページネーション  
　⇨オーディオ一覧、募集一覧等のページで実装。  
 
・レスポンシブ対応(bootstrap)  
　⇨Bootstrapを使用して対応。  
 
・PHPUnit  

・フラッシュメッセージ表示(vue-toasted)  
　⇨ログイン、ログアウト、登録、編集、削除時にメッセージ表示  
 
 
## サイトイメージ
### トップページ  
![screencapture-localhost-10020-2021-06-27-16_26_05](https://user-images.githubusercontent.com/61454264/123538977-53605d80-d772-11eb-9a9f-e993aaf2ca11.png)  

### ユーザーマイページ関連
![スクリーンショット 2021-06-27 18 09 58](https://user-images.githubusercontent.com/61454264/123539071-e4cfcf80-d772-11eb-94d3-292757b35e2e.png)  

### オーディオ関連  
![スクリーンショット 2021-06-27 18 16 39](https://user-images.githubusercontent.com/61454264/123539273-d33af780-d773-11eb-8fea-0dd0490bfaf3.png)  


## 使用技術   
### フロントエンド  
　・Vue.js 2.5.17  
　・vue-router 3.5.1  
　・vuex 3.6.2  
　・vuejs-paginate": 2.1.0  
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







 



