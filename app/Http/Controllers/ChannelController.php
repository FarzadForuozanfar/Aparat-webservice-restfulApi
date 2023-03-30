<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function Update(UpdateChannelRequest $request)
    {
        return ChannelService::updateChannelInfo($request);
    }
}
