<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\LikedVideoRequest;
use App\Http\Requests\Video\ChangeStateRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\ListVideoRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;

class VideoController extends Controller
{
    public function Index(ListVideoRequest $request)
    {
        return VideoService::list($request);
    }
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

    public function ChangeState(ChangeStateRequest $request)
    {
        return VideoService::changeState($request);
    }

    public function Republish(RepublishVideoRequest $request)
    {
        return VideoService::republish($request);
    }

    public function Like(LikeVideoRequest $request)
    {
        return VideoService::likeUnlikeVideo($request);
    }

    public function LikedByCurrentUser(LikedVideoRequest $request)
    {
        return VideoService::likedByCurrentUser($request);
    }
}
