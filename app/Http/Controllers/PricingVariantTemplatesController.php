<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PricingVariantTemplatesController extends Controller
{
    function index(Request $request)
    {
        $rootOption = 'paper_sizes';

        $printOptions = $this->resolveTemplate($rootOption);
        // return response()->json([$rootOption => $printOptions]);

        $result = [];
        $subCategories = config('filters.sub_categories');
        foreach($subCategories as $subCategory) {
            $result[$subCategory['name'].'_options']  = $printOptions;
        }

        return response()->json($result);        
    }

    public function resolveTemplate($option)
    {
        $optionKey = config("pricing_variant_template.$option.options");
        $additionalAttributes = config("pricing_variant_template.$option.additional_attributes");

        $elements = $this->getElements($option, $additionalAttributes, $optionKey);

        return $elements;
    }

    public function getElements($option, $additionalAttributes = [], $optionKey = null)
    {

        $elements = config("print_options.{$option}");
        foreach($elements as $index => $element) {
            if ($optionKey) {
                $options = $this->resolveTemplate($optionKey);
                $additionalAttributes[$optionKey] = $options;
            }
            $elements[$index] = array_merge($element, $additionalAttributes);
        }

        return $elements;
    }
}