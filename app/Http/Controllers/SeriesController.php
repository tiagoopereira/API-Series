<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Http\Controllers\BaseController;

class SeriesController extends BaseController
{
    public function __construct()
    {
        $this->class = Serie::class;
    }
}
