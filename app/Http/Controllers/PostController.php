<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = $this->postService->fetchPosts();
        return ApiResponse::success(
            new PaginatedCollection(
                $posts,
                PostResource::class,
                'posts'
            ),
            'Posts retrieved successfully',
        );
    }

    /**
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $request = $request->validated();
        $this->postService->create($request);
        return ApiResponse::success(null, 'Post created successfully', 201);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        $post = $this->postService->show($post);
        return ApiResponse::success(
            new PostResource($post),
            'Post retrieved successfully',
        );
    }

    /**
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $request = $request->validated();
        $this->postService->update($request, $post->id);
        return ApiResponse::success(null, 'Post updated successfully');
    }

    /**
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->postService->delete($post);
        return ApiResponse::success(null, 'Post deleted successfully');
    }

    /**
     * @return JsonResponse
     */
    public function myPosts(): JsonResponse
    {
        $posts = $this->postService->myPosts();
        return ApiResponse::success(new PaginatedCollection(
            $posts,
            PostResource::class,
            'posts'
        ), 'Posts retrieved successfully');
    }
}
