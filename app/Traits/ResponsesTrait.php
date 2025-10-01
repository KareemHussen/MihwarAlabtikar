<?php

namespace App\Traits;

trait ResponsesTrait
{
    public function respondOk($data, $message = 'Success')
    {
        return response([
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public function respondPaginate($data, $message = 'Success')
    {
        return $data->additional([
            'message' => $message
        ]);
    }

    public function respondCreated($data, $message = 'Created successfully')
    {
        return response([
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    public function respondError($message = 'Error Occured' , $status = 403)
    {
        return response([
            'message' => $message,
        ], $status);
    }

    public function respondUnAuthenticated(string $message = null){

        return response([
            'message' => $message ?? 'Unauthenticated',
        ], 401 );
    }

    public function respondForbidden(string $message = null){

        return response([
            'message' => $message ?? 'Unauthorized',
        ], 403);
    }

    public function respondNotFound($message = 'Not Found')
    {
        return response([
            'message' => $message,
            'errors' => null,
        ], 404);
    }

    public function respondNoContent()
    {
        return response()->noContent();
    }
}
