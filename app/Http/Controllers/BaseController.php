<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Service\ResponseErrorService;

abstract class BaseController extends Controller
{
    protected string $class;

    public function index(Request $request): JsonResponse
    {
        $per_page = $request->per_page;
        $resources = $this->class::paginate($per_page);

        return response()->json($resources);
    }

    public function show(int $id): JsonResponse
    {
        $resource = $this->class::find($id);

        if (!$resource) {
            return ResponseErrorService::json('Resource not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json($resource);
    }

    public function create(Request $request): JsonResponse
    {
        $resource = $this->class::create($request->all());
        return response()->json($resource, JsonResponse::HTTP_CREATED);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $resource = $this->class::find($id);

        if (!$resource) {
            return ResponseErrorService::json('Resource not found', JsonResponse::HTTP_NOT_FOUND);
        }

        $resource->fill($request->all());
        $resource->save();

        return response()->json($resource);
    }

    public function destroy(int $id): JsonResponse
    {
        $removedResources = $this->class::destroy($id);

        if (!$removedResources) {
            return ResponseErrorService::json('Resource not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
