<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->select(['id', 'name'])
            ->latest()
            ->get();
        return ApiResponse::success(CategoryResource::collection
        ($categories), 'Categories retrieved successfully');
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $category = $category->select(['id', 'name'])->getModel();
        return ApiResponse::success(
            new CategoryResource($category),
            'Category retrieved successfully',
        );
    }

    /**
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Category::create($validated);
            return ApiResponse::success(null, 'Category created successfully',
                201);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage(), $th->getCode());
        }
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category):
    JsonResponse
    {
        try {
            $validated = $request->validated();
            $category->update($validated);
            return ApiResponse::success(null, 'Category updated successfully');
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage(), $th->getCode());
        }
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return ApiResponse::success(null, 'Category deleted successfully');
    }

    /**
     * @return JsonResponse
     */
    public function getAllWithPosts(): JsonResponse
    {
        $categories = Category::query()
            ->select(['id', 'name'])
            ->with('posts')
            ->latest()
            ->get();
        return ApiResponse::success(CategoryResource::collection
        ($categories), 'Categories retrieved successfully');
    }

}
