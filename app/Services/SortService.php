<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Builder;

class SortService
{
    public function Sort(Builder $query, string $sort, array $sortableColumns){
        $sortType = explode("-",$sort);
        $column = $sortType[0] ?? "id";
        $direction = $sortType[1] ?? "desc";
        if(!in_array($column,$sortableColumns)) $column = "id";
        return $query->orderBy($column,$direction);
    }
}
