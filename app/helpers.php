<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Response;

if (! function_exists('success_out')) {
    function success_out($data, $links = null, $message = null): Application|Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        if ($links) {
            $links = [
                'count' => $data->count(),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ];
            $data = $data->getCollection();
        }

        return response([
            'success' => true,
            'data' => $data,
            'links' => $links,
            'message' => $message,
        ]);
    }
}

if (! function_exists('error_out')) {
    function error_out($errors, $code = 422, $message = null): Application|Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        return response([
            'success' => false,
            'data' => [],
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}

