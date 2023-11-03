<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $randUsername = Str::random(10);
        DB::table('users')->insert([
            'name' => $randUsername,
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('milkyway!!24'),
        ]);

        $userId = DB::table('users')->where('name', $randUsername)->first();

        DB::table('articles')->insert([
            'user_id' => $userId->id,
            'title' => Str::random(10),
            'content' => Str::random(100),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
