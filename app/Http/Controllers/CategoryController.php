<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->fetchCategories();
        return ApiResponse::success(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
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
        $validated = $request->validated();
        Category::create($validated);
        return ApiResponse::success(null, 'Category created successfully', 201);
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category):
    JsonResponse
    {
        $validated = $request->validated();
        $category->update($validated);
        return ApiResponse::success(null, 'Category updated successfully');
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
        $categories = $this->categoryService->categoryPosts();
        return ApiResponse::success(
            new PaginatedCollection(
                $categories,
                CategoryResource::class,
                'categories'
            ),
            'Categories retrieved successfully'
        );
    }

}
