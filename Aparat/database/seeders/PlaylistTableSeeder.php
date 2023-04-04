<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaylistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Playlist::count()){
            Playlist::truncate();
        }

        $playlists = [
          "لیست پخش 1" ,
          "لیست پخش 2" ,
        ];

        foreach ($playlists as $playlistName){
            Playlist::create([
               'title'=>$playlistName,
               'user_id' => 1
            ]);
        }


        $this->command->info("create these playlists: " . implode(',',$playlists));

    }
}
