<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'category' => [
                'id' => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
            ],
            'thumbnail_url' => $this->thumbnail
                ? asset('storage/' . $this->thumbnail)
                : null,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
