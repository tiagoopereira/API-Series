<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected string $class;

    public function index(): JsonResponse
    {
        $resources = $this->class::all();
        return response()->json($resources);
    }

    public function show(int $id): JsonResponse
    {
        $resource = $this->class::find($id);

        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], JsonResponse::HTTP_NOT_FOUND);
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
            return response()->json(['error' => 'Resource not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $resource->fill($request->all());
        $resource->save();

        return response()->json($resource);
    }

    public function destroy(int $id): JsonResponse
    {
        $removedResources = $this->class::destroy($id);

        if (!$removedResources) {
            return response()->json(['error' => 'Resource not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
