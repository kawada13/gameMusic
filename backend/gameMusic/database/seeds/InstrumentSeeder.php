<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instruments')->insert([
            'name' => 'アコースティックギター',
        ]);
        DB::table('instruments')->insert([
            'name' => 'エレキギター',
        ]);
        DB::table('instruments')->insert([
            'name' => 'ベース',
        ]);
        DB::table('instruments')->insert([
            'name' => 'ドラム',
        ]);
        DB::table('instruments')->insert([
            'name' => 'ピアノ',
        ]);
        DB::table('instruments')->insert([
            'name' => 'オルガン',
        ]);
        DB::table('instruments')->insert([
            'name' => 'フルート',
        ]);
        DB::table('instruments')->insert([
            'name' => 'サックス',
        ]);
        DB::table('instruments')->insert([
            'name' => 'バイオリン',
        ]);
        DB::table('instruments')->insert([
            'name' => 'その他',
        ]);
    }
}
