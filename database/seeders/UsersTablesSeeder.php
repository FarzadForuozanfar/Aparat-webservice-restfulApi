<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser()
    {
        $user = User::factory(1)->create([
            'type' => User::ADMIN_TYPE,
            'name' => 'Farzad'
        ]);
        $this->command->info('created defualt admin user');
    }

    private function createUser()
    {
        $user = User::factory(1)->create([
            'type' => User::USER_TYPE,
            'name' => 'Farzad User'
        ]);
        $this->command->info('created defualt user');
    }
}
