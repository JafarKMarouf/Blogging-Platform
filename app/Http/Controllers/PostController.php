<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->select(['id','title', 'content', 'category_id', 'author_id', 'published_at'])
            ->with(['category:id,name', 'author:id,name'])
            ->latest()
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $posts,
        ]);
    }

    /**
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Post::create($validated);
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        $post = Post::query()
            ->select(['id','title', 'content', 'category_id', 'author_id', 'published_at'])
            ->with(['category:id,name', 'author:id,name'])
            ->find($post->id);
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'data' => [],
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $post,
        ]);
    }

    /**
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $validated = $request->validated();
        $post = Post::query()->find($post->id);
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'data' => [],
            ], 404);
        }
        $post->update($validated);
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }


    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }
}
