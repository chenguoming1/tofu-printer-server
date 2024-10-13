<?php

namespace App\Http\Resources;

use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrintJobCollection extends ResourceCollection
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
            $collectionArr[$index]['printer'] = new PrinterResource(Printer::find($value['printer_id']));
        }

        return $collectionArr;
    }
}
