<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("languages")->insert([
            [
                "code" => "IT",
                "name" => "Italian",
                "native_name" => "Italiano",
            ],
            [
                "code" => "ES",
                "name" => "Spanish",
                "native_name" => "Español",
            ],
            [
                "code" => "DE",
                "name" => "German",
                "native_name" => "Deutsch",
            ],
        ]);
    }
}
