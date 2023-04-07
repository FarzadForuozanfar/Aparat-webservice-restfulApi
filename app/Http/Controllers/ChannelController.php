<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\FollowChannelRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsChannelRequest;
use App\Http\Requests\Channel\UploadAvatarChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function Update(UpdateChannelRequest $request)
    {
        return ChannelService::updateChannelInfo($request);
    }

    public function UploadAvatar(UploadAvatarChannelRequest $request)
    {
        return ChannelService::uploadAvatar4Channel($request);
    }

    public function UpdateSocial(UpdateSocialsChannelRequest $request)
    {
        return ChannelService::UpdateSocial($request);
    }

    public function Follow(FollowChannelRequest $request)
    {
        return ChannelService::followChannel($request);
    }

}
