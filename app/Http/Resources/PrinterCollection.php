<?php

namespace App\Http\Resources;

use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrinterCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $collectionArr = parent::toArray($request);

        foreach ($collectionArr as $index => $value) {
            $pricingPlans = [];
            foreach($value['pricing_plan_ids'] as $pricingPlanId) {
                $pricingPlans[] = new PricingPlanResource(PricingPlan::find($pricingPlanId));
            }
            $collectionArr[$index]['pricing_plans'] = $pricingPlans;
        }

        return $collectionArr;
    }
}
