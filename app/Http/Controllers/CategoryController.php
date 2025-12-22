<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
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
        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $category = $category->select(['id', 'name'])->first();
        return response()->json([
            'status' => 'success',
            'data' => $category,
        ]);
    }

    /**
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Category::create($validated);
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
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
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getAllWithPosts(): JsonResponse
    {
        $categories = Category::query()
            ->select(['id', 'name'])
            ->with('posts:category_id,author_id,title,content')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

}
