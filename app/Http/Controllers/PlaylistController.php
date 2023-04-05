<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\AllPlaylistRequest;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Services\PlaylistService;

class PlaylistController extends Controller
{
    public function Index(AllPlaylistRequest $request)
    {
        return PlaylistService::getAll($request);
    }

    public function My(AllPlaylistRequest $request)
    {
        return PlaylistService::getMyPlaylist($request);
    }

    public function Create(CreatePlaylistRequest $request)
    {
        return PlaylistService::CreatePlaylist($request);
    }

}
