<?php

namespace App\Http\Controllers;

use App\Http\Resources\PricingPlanResource;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PricingPlanController extends Controller
{
    function getPriceRange($variants) {
        $minPrice = '1000';
        $maxPrice = '0.00';
        $printOptions = config('print_options');

        $colors = $printOptions['colors'];
        $sides = $printOptions['sides'];

        foreach ($variants['base_prices'] as $basePrice) {
            foreach ($colors as $color) {
                foreach ($sides as $side) {
                    $price = $basePrice + $color['rate'] + $side['rate'];

                    if ($price < $minPrice) {
                        $minPrice = $price;
                    }
                    if ($price > $maxPrice) {
                        $maxPrice = $price;
                    }
                }
            }
        }

        $priceRange = '$' . $minPrice . ' - ' . '$' . $maxPrice;
        return $priceRange;
    }

    function getPrintOptionDisplayName($key, $name) {
        $printOptions = config('print_options');
        $options = $printOptions[$key];
        foreach($options as $option) {
            if($option['name'] == $name) {
                return $option['display_name'];
            }
        }
    }

    function getVariantDisplay($variants) {
        $basePrices = $variants['base_prices'];
        $paperSizesDisplayName = [];
        foreach($basePrices as $paperSize => $price) {
            $paperSizesDisplayName[] = $this->getPrintOptionDisplayName('paper_sizes', $paperSize);
        }

        return $paperSizesDisplayName;
    }

    function store(Request $request)
    {
        $pricingPlan = $request->all();
        $variants = $pricingPlan['variants'];
        $pricingPlan['price_range'] = $this->getPriceRange($variants);
        $pricingPlan['variant_display'] = $this->getVariantDisplay($variants);

        $printer = PricingPlan::create($pricingPlan);
        return new PricingPlanResource($printer);     
    }

   function update(PricingPlan $pricingPlan, Request $request)
    {
        $data = $request->all();
        $variants = $data['variants'];
        $pricingPlan->fill($request->all());
        $pricingPlan->price_range = $this->getPriceRange($variants);
        $pricingPlan->update();
        
        return new PricingPlanResource($pricingPlan);
    }

    function destroy(PricingPlan $pricingPlan)
    {
        $pricingPlan->delete();
        return response()->json();
    }
}