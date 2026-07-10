<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Searchable {


    public static function limitThePages(Builder $query, Request $request, int $perPage): int
    {
        if ($request->filled('page')) {
            $currentPage = (int) $request->query('page');
            $lastPage =  ceil($query->count() / $perPage);
            if ($currentPage < 1 || $currentPage > $lastPage) {
                return 1;
            }
            return $currentPage;
        }
        return 1;
    }
}