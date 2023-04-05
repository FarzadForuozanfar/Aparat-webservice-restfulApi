<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count())
            User::truncate();

        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser()
    {
        $user = User::factory(1)->create([
            'type' => User::ADMIN_TYPE,
            'name' => 'Farzad',
            'email' => 'admin@aparat.me',
            'mobile' => '+989152363485',
            'password' => bcrypt('123456')
        ]);
        $this->command->info('created defualt admin user');
    }

    private function createUser()
    {
        $user = User::factory(1)->create([
            'type' => User::USER_TYPE,
            'name' => 'Farzad User',
            'email' => 'user@aparat.me',
            'mobile' => '+989212167732',
            'password' => bcrypt('123456')
        ]);
        $this->command->info('created defualt user');
    }
}
