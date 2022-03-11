<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;

class SeriesController extends BaseController
{
    public function __construct()
    {
        $this->class = Serie::class;
    }

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        return parent::create($request);
    }
}
