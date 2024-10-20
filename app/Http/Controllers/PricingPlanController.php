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

        foreach ($variants['paper_sizes'] as $paperSize) {
            foreach ($paperSize['colors'] as $color) {
                foreach ($color['sides'] as $price) {
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
        $variantDisplay = [];
        $paperSizesDisplayName = [];
        $colorsDisplayName = [];
        $sidesDisplayName = [];
        $variantCount = 0;
        foreach($variants['paper_sizes'] as $paperSizeKey => $paperSize) {
            $paperSizesDisplayName[] = $this->getPrintOptionDisplayName('paper_sizes', $paperSizeKey);
            foreach($paperSize['colors'] as $colorKey => $color) {
                $colorsDisplayName[] = $this->getPrintOptionDisplayName('colors', $colorKey);
                foreach($color['sides'] as $sideKey => $price) {
                    $sidesDisplayName[] = $this->getPrintOptionDisplayName('sides', $sideKey);
                    $variantCount++;
                }   
            }
        }
        $variantDisplay[] = collect($sidesDisplayName)->unique()->join('/');
        $variantDisplay = array_merge([collect($colorsDisplayName)->unique()->join('/')], $variantDisplay);
        $variantDisplay = array_merge([collect($paperSizesDisplayName)->unique()->join('.')], $variantDisplay);
        $variantDisplay = array_merge([$variantCount.' Variants'], $variantDisplay);
        return $variantDisplay;
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

   function update(Request $request, PricingPlan $pricingPlan)
    {
        $data = $request->all();
        $variants = $data['variants'];
        // dd($variants);
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