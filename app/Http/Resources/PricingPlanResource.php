<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resourceArr = parent::toArray($request);

        $resourceArr['job_type_display_name'] = $this->getDisplayName($this->job_type, 'job_types');
        $resourceArr['sub_category_display_name'] = $this->getDisplayName($this->sub_category, 'sub_categories');

        return $resourceArr;
    }


    public function getDisplayName($name, $filterType) {
        $filters = config('filters');

        foreach($filters[$filterType] as $filter) {
            if ($filter['name'] == $name) {
                return $filter['display_name'];
            }
        }
    }
}
