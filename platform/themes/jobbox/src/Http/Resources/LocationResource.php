<?php

namespace Theme\Jobbox\Http\Resources;

use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin City|State
 */
class LocationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => implode(', ', array_filter([$this->name, $this->state?->name])),
        ];
    }
}
