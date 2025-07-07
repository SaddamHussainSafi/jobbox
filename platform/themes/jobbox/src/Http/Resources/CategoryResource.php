<?php

namespace Theme\Jobbox\Http\Resources;

use Botble\Blog\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->whenLoaded('slugable'),
            'description' => $this->description,
        ];
    }
}
