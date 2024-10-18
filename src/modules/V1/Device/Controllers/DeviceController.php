<?php

declare(strict_types=1);

namespace Modules\V1\Device\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Modules\V1\Device\Models\Device;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class DeviceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/device/",
     *     summary="Get list of all devices",
     *     tags={"Devices"},
     *     @OA\Response(
     *         response=200,
     *         description="Device data getting successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DeviceResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all devices
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $devices = Device::with('vendor')->get();

        return ResponseHelper::success(data: $devices, message: 'Device data getting successfully. ');
    }
}
