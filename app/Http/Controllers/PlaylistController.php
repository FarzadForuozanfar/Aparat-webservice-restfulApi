<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\AllPlaylistRequest;
use App\Http\Requests\Playlist\AttachVideoRequest;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\ShowPlaylistVideosRequest;
use App\Http\Requests\Playlist\SortVideoRequest;
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
        return PlaylistService::createPlaylist($request);
    }

    public function ShowItems(ShowPlaylistVideosRequest $request)
    {
        return PlaylistService::showVideos($request);
    }

    public function AttachVideo(AttachVideoRequest $request)
    {
        return PlaylistService::attachVideo($request);
    }

    public function SortVideo(SortVideoRequest $request)
    {
        return PlaylistService::sortVideo($request);
    }

}
