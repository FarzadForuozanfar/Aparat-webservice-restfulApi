<?php

namespace Database\Seeders;

use App\Models\PlayList;
use Illuminate\Database\Seeder;

class PlayListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (PlayList::count()){
            PlayList::truncate();
        }

        $playlists = [
            'لیست پخش 1',
            'لیست پخش 2'
        ];

        foreach ($playlists as $playlist)
        {
            PlayList::create(['title' => $playlist , 'user_id' => 1]);
        }

    }
}
