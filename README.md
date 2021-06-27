## Game Music
### 概要

Game Musicはでゲームの様々なシーンにおける「BGM」、「SE」、「ジングル」、「声」を必要とするユーザーに、その音源を届けるサービスです。


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

### 登録/ログイン関連
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

・オーディオ出品(作成)&編集&削除&一覧確認  
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
 
・レスポンシブ対応  
　⇨Bootstrapを使用して対応。  
 
・PHPUnit  

・フラッシュメッセージ表示  
　⇨ログイン、ログアウト、登録、編集、削除時にメッセージ表示  
 
 
## 使用技術
### フロントエンド  
・っs

 



