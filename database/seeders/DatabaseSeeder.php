<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $user = User::factory()->create();

         $token = $user->createToken('My Super Awesome Token');

         $this->command->info('Your API token is: ' . $token->plainTextToken);
    }
}
