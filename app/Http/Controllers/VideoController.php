<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;

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

    public function UploadBanner(UploadVideoBannerRequest $request)
    {
        return VideoService::uploadVideoBanner($request);
    }
}
