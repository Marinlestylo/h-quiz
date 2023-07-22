<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class KeywordQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('keyword_question')->insert([
            'keyword_id' => '1',
            'question_id' => '1'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '1'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '3'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '2'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '4'
        ]);
        
        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '5'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '9'
        ]);

        DB::table('keyword_question')->insert([
            'keyword_id' => '2',
            'question_id' => '10'
        ]);
    }
}
