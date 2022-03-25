<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->insert([
            [
                'title' => 'タイトル１',
                'body' => '内容１内容１内容１内容１内容１',
                'user_id' => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'title' => 'タイトル11',
                'body' => '内容11内容11内容11内容11内容11',
                'user_id' => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'title' => 'タイトル2',
                'body' => '内容2内容2内容2内容2内容2',
                'user_id' => 2,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'title' => 'タイトル22',
                'body' => '内容22内容22内容22内容22内容22',
                'user_id' => 2,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'title' => 'タイトル3',
                'body' => '内容3内容3内容3内容3内容3',
                'user_id' => 3,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'title' => 'タイトル33',
                'body' => '内容33内容33内容33内容33内容33',
                'user_id' => 3,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
