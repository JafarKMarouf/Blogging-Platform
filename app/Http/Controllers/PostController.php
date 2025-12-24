<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->select(['id', 'title', 'content', 'published_at'])
            ->latest()
            ->get();
        return ApiResponse::success(
            PostResource::collection($posts),
            'Posts retrieved successfully',
        );
    }

    /**
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Post::create($validated);
            return ApiResponse::success(null, 'Post created successfully', 201);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage(), $th->getCode());
        }
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        $post = $post->select(['id', 'title', 'content', 'category_id', 'author_id'])
            ->with(['category', 'author'])
            ->first();
        return ApiResponse::success(
            new PostResource($post),
            'Post retrieved successfully',
        );
    }

    /**
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        try {
            $validated = $request->validated();
            $post->update($validated);
            return ApiResponse::success(null, 'Post updated successfully');
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage(), $th->getCode());
        }
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        try {
            $post->delete();
            return ApiResponse::success(null, 'Post deleted successfully');
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage(), $th->getCode());
        }
    }
}
