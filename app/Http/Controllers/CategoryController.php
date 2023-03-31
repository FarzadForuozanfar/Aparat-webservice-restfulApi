<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryListRequest;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function Index(CategoryListRequest $request)
    {
        return CategoryService::getAll($request);
    }

    public function My(CategoryListRequest $request)
    {
        return CategoryService::getMyCategory($request);
    }

    public function Create(CreateCategoryRequest $request)
    {
        return CategoryService::CreateCategory($request);
    }

    public function UploadBanner(UploadCategoryBannerRequest $request)
    {
        return CategoryService::UploadBanner($request);
    }
}
