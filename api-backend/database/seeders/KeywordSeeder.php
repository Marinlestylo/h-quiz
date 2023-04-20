<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Keyword::create(['category' => 'programming', 'name' => 'struct']);
        Keyword::create(['category' => 'programming', 'name' => 'conversion']);
        Keyword::create(['category' => 'programming', 'name' => 'binary']);
        Keyword::create(['category' => 'programming', 'name' => 'ide']);
        Keyword::create(['category' => 'programming', 'name' => 'toolchain']);
        Keyword::create(['category' => 'programming', 'name' => 'goto']);
        Keyword::create(['category' => 'programming', 'name' => 'operators']);
        Keyword::create(['category' => 'programming', 'name' => 'booleans']);
        Keyword::create(['category' => 'programming', 'name' => 'number-bases']);
        Keyword::create(['category' => 'programming', 'name' => 'promotions']);
        Keyword::create(['category' => 'programming', 'name' => 'types']);
        Keyword::create(['category' => 'programming', 'name' => 'algorithms']);
        Keyword::create(['category' => 'programming', 'name' => 'big-o']);
        Keyword::create(['category' => 'programming', 'name' => 'arrays']);
        Keyword::create(['category' => 'programming', 'name' => 'recursion']);
        Keyword::create(['category' => 'programming', 'name' => 'unions']);
        Keyword::create(['category' => 'programming', 'name' => 'compiler']);
        Keyword::create(['category' => 'programming', 'name' => 'bases']);
    }
}
