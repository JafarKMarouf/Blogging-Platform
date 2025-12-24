<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'content' => $this['content'],
            'published_at' => $this['published_at']
                ? $this['published_at']->format('Y-m-d H:i:s')
                : null,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'name' => $this['category']->name,
                ];
            }),
            'author' => $this->whenLoaded('author', function () {
                return [
                    'name' => $this['author']->name,
                ];
            }),
        ];
    }
}
