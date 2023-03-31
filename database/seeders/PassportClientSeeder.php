<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->createPersonalClient();
        $this->createPasswordClient();
    }

    private function createPersonalClient(): void
    {
        DB::table('oauth_clients')->insert([
            'user_id' => null,
            'name' => 'Laravel Personal Access Client',
            'redirect' => env('APP_URL'),
            'secret' => '5Ek0PkvZb52jkQRZfmZZCA7lqQVS0JMhV0lgBsNJ',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createPasswordClient()
    {
        DB::table('oauth_clients')->insert([
            'user_id' => null,
            'name' => 'Laravel Password Access Client',
            'redirect' => env('APP_URL'),
            'secret' => '5Ek0PkvZb52jkQRZfmZZCA7lqQVS0JMhV0lgBsNJ',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
