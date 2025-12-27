<?php

namespace App\Services;

use App\Models\Comment;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{

    /**
     * @return Builder
     */
    public function queryModel(): Builder
    {
        return Comment::query();
    }

    /**
     * @param int $postId
     * @return LengthAwarePaginator
     */
    public function fetchComments(int $postId): LengthAwarePaginator
    {
        return $this->queryModel()
            ->select(['id', 'content', 'author_id', 'created_at'])
            ->where('post_id', $postId)
            ->with(['author:id,name'])
            ->latest()
            ->paginate(10);
    }

    /**
     * @param $request
     * @param int $postId
     * @return void
     */
    public function create($request, int $postId): void
    {
        $this->queryModel()
            ->create([
                'post_id' => $postId,
                'author_id' => auth()->id(),
                'content' => $request['content'],
            ]);
    }

    /**
     * @param $request
     * @param  $comment
     * @return Comment
     * @throws Exception
     */
    public function update($request, $comment): Comment
    {
        if ($comment->author_id != auth()->id()) {
            throw new Exception('You do not have permission to update this comment.', 403);
        }
        $this->queryModel()
            ->where('id', $comment->id)
            ->update(['content' => $request['content']]);
        $comment->refresh();
        return $comment;
    }

    /**
     * @param $comment
     * @return void
     * @throws Exception
     */
    public function delete($comment): void
    {
        if ($comment->author_id != auth()->id()) {
            throw new Exception('You do not have permission to update this comment.', 403);
        }
        $comment->delete();
    }
}
