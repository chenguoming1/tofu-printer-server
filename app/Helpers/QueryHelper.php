<?php
namespace App\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder;

class QueryHelper
{
    public static function getQuery($request, $query, $filters)
    {
        $filters = $request->input('filters', []);
        foreach ($filters as $filter) {
            $filter = json_decode($filter, true);
            static::filter($query, $filter);
        }

        return $query;
    }

    public static function filter($query, $filter) {
        $operator = strtoupper(data_get($filter, 'operator', '='));
        $value = $filter['value'];

        $value = $operator == 'SEARCH' ? "%{$value}%" : $value;

        if ($operator == 'SEARCH') {
            $query->where(function(Builder $query) use ($filter, $value) {
                foreach($filter['key'] as $index => $key) {
                    $query->orWhere($key, 'like', $value);
                }
            });

            return;
        }

        if (is_array($filter['key'])) {
            foreach($filter['key'] as $index => $key) {
                $query->where($key, $operator, $value);
            }
            return;
        }
        
        $key = $filter['key'];
        $query->where($key, $operator, $value);
    }
}