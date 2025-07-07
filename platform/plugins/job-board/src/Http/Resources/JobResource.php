<?php

namespace Botble\JobBoard\Http\Resources;

use Botble\JobBoard\Models\Job;
use Botble\Location\Http\Resources\CityResource;
use Botble\Location\Http\Resources\StateResource;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Job
 */
class JobResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge([
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'description' => $this->description,
            'image' => $this->company->logo ? RvMedia::getImageUrl(
                $this->company->logo,
                'small',
                false,
                RvMedia::getDefaultImage()
            ) : null,
            'company' => new CompanyResource($this->company),
            'date' => $this->created_at->translatedFormat('M d, Y'),
        ], is_plugin_active('location') ? [
            'city' => new CityResource($this->city),
            'state' => new StateResource($this->state),
        ] : []);
    }
}
