<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentListRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function Index (CommentListRequest $request): array
    {
        return CommentService::getAll($request);
    }

//    public function Create (CreateTagRequest $request)
//    {
//        return TagService::CreateTag($request);
//    }
}
