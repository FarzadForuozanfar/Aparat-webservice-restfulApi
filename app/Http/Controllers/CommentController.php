<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function Index (CreateCommentRequest $request): array
    {
        return CommentService::getAll($request);
    }

    public function Create (CreateCommentRequest $request)
    {
        return CommentService::CreateComment($request);
    }
}
