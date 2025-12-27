<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param $data
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public static function success($data, $message = null, int $code = 200):
    JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param $message
     * @param $code
     * @return JsonResponse
     */
    public static function error($message, $code): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    /**
     * @param Validator $validator
     * @return JsonResponse
     */
    public static function validationError(Validator $validator): JsonResponse
    {
        $errors = $validator->errors();

        $firstError = $errors->first();

        $count = $errors->count();
        $remainder = $count - 1;

        $customMessage = $remainder > 0
            ? $firstError . " (and {$remainder} more error" . ($remainder > 1 ? 's' : '') . ")"
            : $firstError;

        return response()->json([
            'status' => false,
            'message' => $customMessage,
            'data' => $errors->all(),
        ], 422);
    }
}
