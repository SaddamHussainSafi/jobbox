<?php

namespace Botble\JobBoard\Http\Resources;

use Botble\JobBoard\Models\Package;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Package
 */
class PackageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'description' => $this->description,
            'is_default' => $this->is_default,
            'price' => $this->price,
            'price_text' => $this->price_text,
            'price_per_post_text' => $this->price_per_post_text,
            'percent_save' => $this->percent_save,
            'number_of_listings' => $this->number_of_listings,
            'number_posts_free' => $this->number_posts_free,
            'price_text_with_sale_off' => $this->price_text_with_sale_off,
            'features' => $this->formatted_features,
        ];
    }
}
