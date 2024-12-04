<?php

declare(strict_types=1);

namespace Modules\V1\Device\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
     *     tags={"DeviceVendors"},
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
        try {
            $deviceVendors = DeviceVendor::with('device')->get();

            return ResponseHelper::success(DeviceVendorResource::collection($deviceVendors), 'Device vendors data getting successfully. ');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
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
            dd($request->all());
            // Device vendor Creation
            $deviceVendor = new DeviceVendor();
            $deviceVendor->name = $request->name;
            $deviceVendor->status = $request->status;
            $deviceVendor->save();

            DB::commit();

            return ResponseHelper::success(new DeviceVendorResource($deviceVendor), 'Device Vendor created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/device/vendor/{id}",
     *     summary="Show a specific Device Vendor",
     *     operationId="showDeviceVendor",
     *     tags={"DeviceVendors"},
     *     description="Fetch details of a Device Vendor by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Device Vendor to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Device Vendor details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Device Vendor data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/DeviceVendorResource"),
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized access"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=401)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid Device Vendor ID"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An error occurred on the server"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $deviceVendor = DeviceVendor::with('device')->findOrFail($id);
            if ( ! $deviceVendor) {
                return ResponseHelper::error('Device Vendor not found');
            }

            return ResponseHelper::success(new DeviceVendorResource($deviceVendor), 'Device Vendor data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/device/vendor/{id}",
     *     summary="Update a specific Device Vendor",
     *     operationId="updateDeviceVendor",
     *     tags={"DeviceVendors"},
     *     description="Update the details of a Device Vendor by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Device Vendor to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Device Vendor data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="My company", description="Device Vendor name"),
     *                  @OA\Property(property="status", type="string", example="Active", description="Device Vendor status")
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Device Vendor updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/DeviceResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Device Vendor not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:155',
                    Rule::unique('device_vendors', 'name')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
                ],
                'status' => 'required|string|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $deviceVendor = DeviceVendor::findOrFail($id);
            if ( ! $deviceVendor) {
                return ResponseHelper::error('Device Vendor not found');
            }

            $deviceVendor->update($request->all());
            DB::commit();

            return ResponseHelper::success(new DeviceVendorResource($deviceVendor), 'Device Vendor updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/device/Vendor/{id}",
     *     summary="Delete Vendor a specific Device",
     *     operationId="deleteDeviceVendor",
     *     tags={"DeviceVendors"},
     *     description="Delete a Device Vendor by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Device Vendor to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Device Vendor deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Device Vendor not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $deviceVendor = DeviceVendor::findOrFail($id);
            if ( ! $deviceVendor) {
                return ResponseHelper::error('Device Vendor not found');
            }

            $deviceVendor->delete();

            DB::commit();

            return ResponseHelper::success(null,'Device Vendor deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }
}
