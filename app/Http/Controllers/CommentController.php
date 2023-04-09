<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\ChangeStateCommentRequest;
use App\Http\Requests\Comment\CommentListRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function Index (CommentListRequest $request): array
    {
        return CommentService::getAll($request);
    }

    public function Create (CreateCommentRequest $request)
    {
        return CommentService::createComment($request);
    }

    public function ChangeState (ChangeStateCommentRequest $request)
    {
        return CommentService::changeState($request);
    }

    public function Delete (DeleteCommentRequest $request)
    {
        return CommentService::delete($request);
    }
}
