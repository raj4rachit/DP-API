<?php

declare(strict_types=1);

namespace Shared\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ResponseHelper
{
    /**
     * Generate a successful JSON response.
     *
     * @param  mixed|null  $data  The data to include in the response.
     * @param  string|null  $message  An optional message to include in the response.
     * @param  int  $statusCode  The HTTP status code for the response (default is 200).
     * @param  array  $meta  Additional meta information to include in the response.
     */
    public static function success(mixed $data = null, ?string $message = null, int $statusCode = 200, array $meta = []): JsonResponse
    {
        $response = ['status' => 'success', 'statusCode' => $statusCode];
        if ($data) {
            $response['data'] = $data;
        }

        if ( ! empty($meta)) {
            foreach ($meta as $key => $value) {
                $response[$key] = $value;
            }
        }

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Generate an error JSON response.
     *
     * @param  string  $message  The error message (default is 'Oops something went wrong').
     * @param  int  $statusCode  The HTTP status code for the response (default is 500).
     */
    public static function error(string $message = 'Oops something went wrong', int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * If User don't have any of the permission that is specified in Array then Redirect will happen
     *
     * @return RedirectResponse|true
     */
//    public static function noAnyPermissionThenRedirect(array $permissions)
//    {
//        return self::error("You Don't have enough permissions", 403);
//        if ( ! Auth::user()->canany($permissions)) {
//            return self::error("You Don't have enough permissions", 403);
//        }
//        return true;
//    }
}
