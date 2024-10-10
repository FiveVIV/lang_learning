<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("sets")->insert([
            [
                "name" => "Beginner 1 - 10",
                "model_id" => 1,
                "model_type" => "App\Models\Language",
            ]
        ]);
    }
}
