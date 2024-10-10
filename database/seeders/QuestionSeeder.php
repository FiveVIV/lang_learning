<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("questions")->insert([
            [
                "set_id" => "1",
                "correct_answer_id" => "1",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "2",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "3",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "4",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "5",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "6",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "7",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "8",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "9",
            ],
            [
                "set_id" => "1",
                "correct_answer_id" => "10",
            ],
        ]);
    }
}
