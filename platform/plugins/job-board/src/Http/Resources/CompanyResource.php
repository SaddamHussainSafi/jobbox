<?php

namespace Botble\JobBoard\Http\Resources;

use Botble\JobBoard\Models\Company;
use Botble\Location\Http\Resources\CityResource;
use Botble\Location\Http\Resources\CountryResource;
use Botble\Location\Http\Resources\StateResource;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Company
 */
class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(
            [
                'id' => $this->id,
                'name' => $this->name,
                'address' => $this->address,
                'accounts' => AccountResource::collection($this->accounts),
                'logo' => RvMedia::getImageUrl($this->logo),
                'logo_thumb' => $this->logo_thumb,
                'url' => $this->url,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            is_plugin_active('location') ? [
                'country' => new CountryResource($this->country),
                'state' => new StateResource($this->state),
                'city' => new CityResource($this->city),
            ] : []
        );
    }
}
