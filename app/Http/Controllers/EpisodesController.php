<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Service\ResponseErrorService;
use App\Http\Controllers\BaseController;

class EpisodesController extends BaseController
{
	public function __construct()
   	{
        $this->class = Episode::class;
   	}

	
	public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'season' => 'required',
			'number' => 'required',
			'serie_id' => 'required'
        ]);

        return parent::create($request);
    }

	
   	public function getSerieEpisodes(int $serieId): JsonResponse
   	{
		$serie = Serie::find($serieId);

		if (!$serie) {
			return ResponseErrorService::json('Resource not found', JsonResponse::HTTP_NOT_FOUND);
		}

		$episodes = Episode::query()->where('serie_id', $serie->id)->paginate();

        return response()->json($episodes);
   	}
}
