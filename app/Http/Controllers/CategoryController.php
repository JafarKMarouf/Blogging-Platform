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
            ->select('name')
            ->latest()
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * @param int $categoryId
     * @return JsonResponse
     */
    public function show(int $categoryId): JsonResponse
    {
        $category = Category::query()
            ->select('name')
            ->find($categoryId);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'data' => [],
            ], 404);
        }
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
     * @param int $categoryId
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, int $categoryId):
    JsonResponse
    {
        $validated = $request->validated();
        $category = Category::query()
            ->find($categoryId);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'data' => [],
            ], 404);
        }
        $category->update($validated);
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    /**
     * @param int $categoryId
     * @return JsonResponse
     */
    public function destroy(int $categoryId): JsonResponse
    {
        $category = Category::query()
            ->find($categoryId);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'data' => [],
            ], 404);
        }
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
