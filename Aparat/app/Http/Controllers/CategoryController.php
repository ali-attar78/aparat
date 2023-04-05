<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\ListCategoryRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function index(ListCategoryRequest $request)
    {
        return CategoryService::getAllCategories($request);
    }

    public function my(ListCategoryRequest $request)
    {
        return CategoryService::getMyCategories($request);
    }

    public function uploadBanner(UploadCategoryBannerRequest $request)
    {
        return CategoryService::uploadBanner($request);

    }

    public function create(CreateCategoryRequest $request)
    {
        return CategoryService::create($request);
    }



}
