<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PricingPlanCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parentResult = parent::toArray($request);

        foreach ($parentResult as $index => $pricingPlan) {
            $printOptions = config('print_options');
            $colors = $printOptions['colors'];
            $sides =  $printOptions['sides'];
            $colorRates = collect($colors)->pluck('rate', 'name');
            $sideRates = collect($sides)->pluck('rate', 'name');

            $parentResult[$index]['color_rates'] = $colorRates;
            $parentResult[$index]['side_rates'] = $sideRates;
        }

        return $parentResult;
    }
}
