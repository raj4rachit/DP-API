<?php

declare(strict_types=1);

namespace Modules\V1\Device\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\V1\Device\Models\Device;
use Modules\V1\Device\Requests\DeviceCreateRequest;
use Modules\V1\Device\Resources\DeviceResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class DeviceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/device",
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

    /**
     * @OA\Post(
     *     path="/device",
     *     summary="Create a new Device",
     *     operationId="createDevice",
     *     tags={"Devices"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Device with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="name", type="string", description="vendor company name"),
     *                 @OA\Property(property="status", type="string", description="Status"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Device created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Device created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/DeviceResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(DeviceCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Device vendor Creation
            $device = new Device();
            $device->name = $request->name;
            $device->status = $request->status;
            $device->save();

            DB::commit();

            return ResponseHelper::success(data: new DeviceResource($device), message: 'Device created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
