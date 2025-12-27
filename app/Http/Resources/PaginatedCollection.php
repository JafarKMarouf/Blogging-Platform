<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedCollection extends ResourceCollection
{
    protected mixed $resourceClass;
    protected string $dataKey;

    public function __construct($resource, $resourceClass, $dataKey = 'items')
    {
        parent::__construct($resource);

        $this->resourceClass = $resourceClass;
        $this->dataKey = $dataKey;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            $this->dataKey => $this->resourceClass::collection($this->collection),

            'pagination' => [
                'current_page' => $this->resource->currentPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'last_page' => $this->resource->lastPage(),
            ],
        ];
    }
}
