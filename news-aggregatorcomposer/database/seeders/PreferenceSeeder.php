<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        UserPreference::create([
            'user_id' => $user->id,
            'preferred_source' => 'The Guardian',
            'preferred_category' => 'article',
            'preferred_author' => null,
        ]);
    }
}
