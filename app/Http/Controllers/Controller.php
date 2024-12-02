<?php

namespace App\Http\Controllers;
use App\Services\SortService;

abstract class Controller
{
    protected $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }
}
