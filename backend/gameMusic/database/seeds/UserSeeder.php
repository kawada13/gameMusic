<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('adminuser'),
            'scope' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'ゲストユーザー',
            'email' => 'guest@gmail.com',
            'password' => bcrypt('guestuser'),
            'scope' => 2,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 2,
            'introduce' => 'ゲストユーザーです',
            'instrument' => 'エレキギター、ドラム',
        ]);

        DB::table('users')->insert([
            'name' => 'しん',
            'email' => 'a@gmail.com',
            'password' => bcrypt('aaaaaa'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 3,
            'introduce' => 'しんです。よろしくお願いします。',
            'instrument' => 'エレキギター、ドラム、ベース、サックス',
        ]);
        DB::table('recruitments')->insert([
            'user_id' => 3,
            'title' => 'ラスボス線で使うBGMが欲しいです。',
            'content' => 'タイトルにあるようにラスボスで使うようなBGMが欲しいです。\n
            予算は大体5,000~10,000くらいで考えています。\n
            よろしくお願いします！',
        ]);

        DB::table('users')->insert([
            'name' => '隼人',
            'email' => 'b@gmail.com',
            'password' => bcrypt('bbbbbb'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 4,
            'introduce' => '隼人です。よろしくお願いします。\nラウド系音楽が好きです。',
            'instrument' => 'エレキギター、ドラム、ベース、サックス、ピアノ',
        ]);

        DB::table('users')->insert([
            'name' => 'aya_load',
            'email' => 'c@gmail.com',
            'password' => bcrypt('cccccc'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 5,
            'introduce' => 'よろしくお願いします。\n効果音作りが得意です。',
            'instrument' => 'Cubase 8でKOMPLETE 11 ultimate、Cinesamples社、Output社、Spectrasonics社の製品を\nメインに使用し製作しております。',
        ]);
        DB::table('recruitments')->insert([
            'user_id' => 5,
            'title' => 'ファミコン系、切なく悲しい音源募集します',
            'content' => '予算は大体5,000~10,000くらいで考えています。\n
            なるべく聞いてて懐かしさを感じさせるような感じがいいです。',
        ]);

        DB::table('users')->insert([
            'name' => 'taku',
            'email' => 'd@gmail.com',
            'password' => bcrypt('dddddd'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 6,
            'introduce' => 'ポップめのイージーリスニング、アコースティック系のBGMを中心に作曲しており\n
            インストなりにメロディ性の強いもの、のどかや切ない雰囲気を得意としております。\n',
            'instrument' => 'エレキギター、サックス、ピアノ',
        ]);
        DB::table('recruitments')->insert([
            'user_id' => 6,
            'title' => '日常会話での効果音',
            'content' => '\n
            なるべく聞いてて懐かしさを感じさせるような感じがいいです。',
        ]);

        DB::table('users')->insert([
            'name' => 'taku',
            'email' => 'e@gmail.com',
            'password' => bcrypt('eeeeee'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 7,
            'introduce' => 'ポップめのイージーリスニング、アコースティック系のBGMを中心に作曲しており\n
            インストなりにメロディ性の強いもの、のどかや切ない雰囲気を得意としております。\n',
            'instrument' => 'ピアノ：CinePiano、Ivory II Italian Grand、Mercury\n
            ドラム：Addictive Drums2 Monumental Bundle、Steven Slate Drums4、SD2.0',
        ]);
        DB::table('recruitments')->insert([
            'user_id' => 7,
            'title' => 'シリアスシーンのBGMが欲しいです。',
            'content' => '友人を裏切るシーンで使うことを想定しています。\n
            なるべく聞いてて不安を感じさせるような感じがいいです。\n
            秒数的には1:30~2:30kくらいを考えています。',
        ]);
        DB::table('recruitments')->insert([
            'user_id' => 7,
            'title' => '回復のジングル',
            'content' => '回復のジングルを探しています。\n
            ドラクエみたいに聞いてて回復してると響かせてくれるような感じを求めてます！\n
            もし出来れば一度通話などして、相談できればなと思います!どうか皆さんよろしくお願いいたします。',
        ]);

        DB::table('users')->insert([
            'name' => 'Mutou',
            'email' => 'f@gmail.com',
            'password' => bcrypt('ffffff'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 8,
            'introduce' => '音楽プロデューサー。お気軽にご相談を♪',
            'instrument' => 'MacBookPro2017、CubasePro10、C12VR、AVALON M5、1176LN、MOTU828mk3、dynaudioDM5',
        ]);

        DB::table('users')->insert([
            'name' => 'RYO',
            'email' => 'g@gmail.com',
            'password' => bcrypt('gggggg'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 9,
            'introduce' => '”生き生きとした表現力”
            コンピューターを使用した音楽制作で失われがちな人間味、生き生きとした表現を損なわない楽曲作りにこだわり制作しております.',
            'instrument' => 'Cubase 8でKOMPLETE 11 ultimateを使用。',
        ]);
        DB::table('recruitments')->insert([
            'user_id' => 9,
            'title' => '幻想的なピアノバラードを募集します。',
            'content' => '幻想的なピアノバラードを募集します。\n
            ラスボス前の重要なシーンで使う想定です。\n
            長めの2:30くらいがいいです。\n
            もし出来れば一度メッセージのやりとりを通して、より具体的に相談できたらなとおもいます。',
        ]);

        DB::table('users')->insert([
            'name' => 'MON SOUND',
            'email' => 'h@gmail.com',
            'password' => bcrypt('hhhhhh'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 10,
            'introduce' => 'ゲーム･映像アプリや地方ラジオ、同人ゲームへのBGM提供\n
            ご当地アイドルへの楽曲提供経験ありです。よろしくお願いします。',
            'instrument' => 'ギター：Ample Sound Series、Prominy V-METAL\n
            ベース：Prominy SR5、Trilian',
        ]);

        DB::table('users')->insert([
            'name' => 'RISA MUSIC',
            'email' => 'i@gmail.com',
            'password' => bcrypt('iiiiii'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 11,
            'introduce' => '沖縄県在住のフリーランス作曲・編曲家です。\n
            ゲーム向けのBGMや歌モノの楽曲制作依頼をいただくことが多いです。',
            'instrument' => 'メインスピーカー：YAMAHA MSP5 STUDIO\n
            サブウーファー：YAMAHA HS8S',
        ]);

        DB::table('users')->insert([
            'name' => '安藤',
            'email' => 'j@gmail.com',
            'password' => bcrypt('jjjjjj'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 12,
            'introduce' => '安藤です。ゲームミュージック制作における数々の賞を受賞しています。よろしくお願いいたします。',
            'instrument' => 'メインスピーカー：YAMAHA MSP5 STUDIO\n
            ピアノ：CinePiano',
        ]);

        DB::table('users')->insert([
            'name' => '高宮',
            'email' => 'k@gmail.com',
            'password' => bcrypt('kkkkkk'),
            'scope' => 0,
        ]);
        DB::table('user_informations')->insert([
            'user_id' => 13,
            'introduce' => 'BGMはEDMやHIP-HOP、アンビエント、エレクトロニカ、チップチューンなどを中心に制作。\n
            どこかに個性を感じるワクワクするような楽曲を心がけて制作しています。\n
            自身による歌やRAPのボーカル収録による歌モノ楽曲も制作・登録しております。\n\n
            【BGM・インストゥルメンタル実績】\n
            １０９ファッションブランドへの楽曲提供\n
            ゲームアプリへのBGM提供\n
            オリジナルソングの伴奏制作',
            'instrument' => 'DAW:cubase pro\n
            I/F:Antelope audio　Discrete4 synergy Core\n
            Mic：audio‐technica AT4040 , Antelope audio Edge solo',
        ]);
    }
}
