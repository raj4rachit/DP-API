<?php

declare(strict_types=1);

namespace Modules\V1\Device\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Modules\V1\Device\Models\DeviceVendor;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class DeviceVendorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/device/vendor",
     *     summary="List all device vendors",
     *     operationId="listDeviceVendors",
     *     tags={"Device Vendors"},
     *     description="Retrieve a list of all device vendors.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of device vendors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="device vendors data retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/DeviceVendorResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     *
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index(): JsonResponse
    {
        $deviceVendors = DeviceVendor::get();

        return ResponseHelper::success(data: $deviceVendors, message: 'Device vendors data getting successfully. ');
    }
}
