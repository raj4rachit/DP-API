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
     *     path="/device/vendor/",
     *     summary="Get list of all device vendors",
     *     tags={"Device Vendors"},
     *     @OA\Response(
     *         response=200,
     *         description="Device vendors data getting successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DeviceVendorResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all device vendors
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $deviceVendors = DeviceVendor::get();

        return ResponseHelper::success(data: $deviceVendors, message: 'Device vendors data getting successfully. ');
    }
}
