<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("question_user")->insert([
            [
                "user_id" => 1,
                "question_id" => 1,
                "correctly_answered" => true,
            ],
            [
                "user_id" => 1,
                "question_id" => 2,
                "correctly_answered" => true,
            ],
            [
                "user_id" => 1,
                "question_id" => 3,
                "correctly_answered" => false,
            ],
            [
                "user_id" => 1,
                "question_id" => 4,
                "correctly_answered" => true,
            ],
            [
                "user_id" => 1,
                "question_id" => 5,
                "correctly_answered" => false,
            ],
            [
                "user_id" => 1,
                "question_id" => 6,
                "correctly_answered" => false,
            ],
        ]);
    }
}
