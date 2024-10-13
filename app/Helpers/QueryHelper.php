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
            $operator = strtoupper(data_get($filter, 'operator', '='));
            if ($operator == 'SEARCH') {
                $query->where(function(Builder $query) use ($filter) {
                    foreach ($filter['key'] as $key) {
                        $query->orWhere($key, 'like', '%'.$filter['value'].'%');
                    }
                });
                continue;
            }
            $query->where($filter['key'], $filter['value']);
        }

        return $query;
    }
}