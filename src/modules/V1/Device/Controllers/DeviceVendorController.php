<?php

declare(strict_types=1);

namespace Modules\V1\Device\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\V1\Device\Models\DeviceVendor;
use Modules\V1\Device\Requests\DeviceVendorCreateRequest;
use Modules\V1\Device\Resources\DeviceVendorResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
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
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="device vendors data retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *
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

    /**
     * @OA\Post(
     *     path="/device/vendor/",
     *     summary="Create a new Device vendor",
     *     operationId="createDeviceVendor",
     *     tags={"DeviceVendors"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Device vendorv with the provided information.",
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
     *         description="Device vendor created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Device vendor created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/DeviceVendorResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(DeviceVendorCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Device vendor Creation
            $deviceVendor = new DeviceVendor();
            $deviceVendor->name = $request->name;
            $deviceVendor->status = $request->status;
            $deviceVendor->save();

            DB::commit();

            return ResponseHelper::success(data: new DeviceVendorResource($deviceVendor), message: 'Device Vendor created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
