<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->articleID,
            'title' => $this->title,
            'description' => $this->body,
            'category' => $this->category,
            'image' => is_null($this->image) ? asset('storage/ArticlesImages/default.png') : asset('storage/'. $this->image),
            'date' => $this->created_at->format('d-m-Y')

        ];
    }
}
