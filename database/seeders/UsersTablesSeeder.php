<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count()){
            User::truncate();
            Channel::truncate();
        }

        $this->createAdminUser();
        for ($i = 0; $i < 9; $i++)
            $this->createUser($i);
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

    private function createUser($num = 1)
    {
        $user = User::factory(1)->create([
            'type' => User::USER_TYPE,
            'name' => 'User' . $num,
            'email' => 'user' . $num .'@aparat.me',
            'mobile' => '+989' . str_repeat($num, 9),
            'password' => bcrypt('123456')
        ]);
        $this->command->info('created' . $num .' user');
    }
}
