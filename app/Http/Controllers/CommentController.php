<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Exception;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService)
    {
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function index(Post $post): JsonResponse
    {
        $comments = $this->commentService->fetchComments($post->id);
        return ApiResponse::success(
            new PaginatedCollection(
                $comments,
                CommentResource::class,
                'comments'
            ),
            'Comments retrieved successfully',
        );
    }

    /**
     * @param CommentRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        $request = $request->validated();
        $this->commentService->create($request, $post->id);
        return ApiResponse::success(null, 'Comment created', 201);
    }

    /**
     * @param CommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     * @throws Exception
     */
    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        $request = $request->validated();
        $data = $this->commentService->update($request, $comment);
        return ApiResponse::success(new CommentResource($data), 'Comment updated');
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->delete($comment);
        return ApiResponse::success(null, 'Comment deleted');
    }
}
