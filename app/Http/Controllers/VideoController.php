<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function Upload(UploadVideoRequest $request)
    {
        return VideoService::uploadVideo($request);
    }

    public function Create(CreateVideoRequest $request)
    {
        return VideoService::createVideo($request);
    }
}
