<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call(UsersTablesSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PassportClientSeeder::class);
        $this->call(PlayListTableSeeder::class);

        Schema::enableForeignKeyConstraints();

        $this->command->info('Clear all aparat tmp files');
        Artisan::call('aparat:clear');

    }
}
