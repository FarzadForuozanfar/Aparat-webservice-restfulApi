<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Services\TagService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function Index (Request $request): Collection
    {
        return TagService::getAll($request);
    }

    public function Create (CreateTagRequest $request)
    {
        return TagService::CreateTag($request);
    }
}
