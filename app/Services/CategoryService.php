<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    /**
     * @return Builder
     */
    public function queryModel(): Builder
    {
        return Category::query();
    }

    /**
     * @return Collection
     */
    public function fetchCategories(): Collection
    {
        return $this->queryModel()
            ->select(['id', 'name'])
            ->latest()
            ->get();
    }


    /**
     * @return LengthAwarePaginator
     */
    public function categoryPosts(): LengthAwarePaginator
    {
        return $this->queryModel()
            ->select(['id', 'name'])
            ->with(['posts' => function ($q) {
                $q->select('id', 'category_id', 'title')
                    ->latest()
                    ->get();
            }])
            ->latest()
            ->paginate(5);
    }

}
