<?php

namespace App\Traits;

use App\Constants\ApiMessages;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponses
{

    public static function read(JsonResource $resource): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => ApiMessages::READ->success(),
            'data' => $resource
        ];

        if ($resource->resource instanceof Paginator) {
            $response['pagination'] = [
                'current_page' => $resource->resource->currentPage(),
                'per_page' => $resource->resource->perPage(),
                'total' => $resource->resource->total(),
                'last_page' => $resource->resource->lastPage(),
            ];
        }
        return response()->json($response, 200);
    }

    public static function create(JsonResource $resource): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => ApiMessages::CREATE->success(),
            'data' => $resource
        ], 201);
    }

    public static function updated(JsonResource $resource): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => ApiMessages::UPDATE->success(),
            'data' => $resource
        ], 201);
    }

    public static function delete(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => ApiMessages::DELETE->success(),
        ], 201);
    }

    public static function failedRead(): JsonResponse
    {
        return response()->json([
            "status" => false,
            'message' => ApiMessages::READ->error(),
        ], 404);
    }

    public static function failedCreate(): JsonResponse
    {
        return response()->json([
            "status" => false,
            'message' => ApiMessages::CREATE->error(),
        ], 404);
    }

    public static function failedUpdate(): JsonResponse
    {
        return response()->json([
            "status" => false,
            'message' => ApiMessages::UPDATE->error(),
        ], 404);
    }

    public static function failedDelete(): JsonResponse
    {
        return response()->json([
            "status" => false,
            'message' => ApiMessages::DELETE->error(),
        ], 404);
    }
}
