<?php

namespace App\Services;

use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    /**
     * @return Builder
     */
    public function queryModel(): Builder
    {
        return Post::query();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function fetchPosts(): LengthAwarePaginator
    {
        return $this->queryModel()
            ->select(['id', 'title', 'content', 'author_id', 'published_at'])
            ->with('author')
            ->latest()
            ->paginate(10);
    }

    /**
     * @param $post
     * @return Post
     */
    public function show($post): Post
    {
        return $post
            ->select(['id', 'title', 'content', 'category_id', 'author_id', 'published_at'])
            ->with(['category', 'author'])
            ->getModel();
    }

    /**
     * @param $request
     * @return void
     */
    public function create($request): void
    {
        $this
            ->queryModel()
            ->create([
                'title' => $request['title'],
                'content' => $request['content'],
                'category_id' => $request['category_id'],
                'author_id' => auth()->id(),
                'published_at' => \Illuminate\Support\now(),
            ]);
    }

    /**
     * @param $request
     * @param $post
     * @return void
     * @throws Exception
     */
    public function update($request, $post): void
    {
        if ($post->author_id != auth()->id()) {
            throw new Exception('You do not have permission to update this comment.', 403);
        }
        $post->update($request);
    }

    /**
     * @param $post
     * @return void
     * @throws Exception
     */
    public function delete($post): void
    {
        if ($post->author_id != auth()->id()) {
            throw new Exception('You do not have permission to delete this post.', 403);
        }
        $post->delete();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function myPosts(): LengthAwarePaginator
    {
        return $this->queryModel()
            ->select(['id', 'title', 'content', 'author_id', 'published_at', 'category_id'])
            ->where('author_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);
    }

}
