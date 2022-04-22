<?php

namespace App\Traits;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return http response OK
     * @param mix $data
     * @param int $status
     * @param string $message
     */
    public function responseOk($data, int $status=JsonResponse::HTTP_OK, string $message="Berhasil")
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Return http response OK
     * @param mix $data
     * @param int $status
     * @param string $message
     */
    public function responseError($errors, int $status=JsonResponse::HTTP_INTERNAL_SERVER_ERROR, string $message='Terjadi kesalahan')
    {
        return response()->json([
            'message' => $message, 
            'errors' => $errors
        ], $status);
    }


}