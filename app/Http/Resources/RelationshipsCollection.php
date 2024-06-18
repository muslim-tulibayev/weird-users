<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RelationshipsCollection extends ResourceCollection
{
    public static $wrap = 'relationships';

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function paginationInformation($request, $paginated, $default)
    {
        return [
            'count' => $this->resource->total(),
            'next' => $this->nextPageUrl(),
            'previous' => $this->previousPageUrl(),
        ];
    }
}
