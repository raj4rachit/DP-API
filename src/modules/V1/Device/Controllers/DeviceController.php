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
use Modules\V1\Device\Models\Device;
use Modules\V1\Device\Requests\DeviceCreateRequest;
use Modules\V1\Device\Resources\DeviceResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class DeviceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/device",
     *     summary="Get list of all devices",
     *     tags={"Devices"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Device data getting successfully",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/DeviceResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all devices
     */
    public function index(): JsonResponse
    {
        try {
            $devices = Device::with('vendor')->get();

            return ResponseHelper::success(DeviceResource::collection($devices), 'Device data getting successfully.');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
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
     *                  @OA\Property(property="name", type="string", example="My company", description="Device name"),
     *                   @OA\Property(property="device_vendor_id", type="string", example="safsdfsf", description="Device Vendor id"),
     *                   @OA\Property(property="api_key", type="string", example="Mydsfsdfds", description="Device api key"),
     *                   @OA\Property(property="device_type", type="string", example="My company", description="Device type"),
     *                   @OA\Property(property="status", type="string", example="Active", description="Device status"),
     *                   @OA\Property(property="device_sim", type="string", example="No", description="Device Sim"),
     *                   @OA\Property(property="secret_key", type="string", example="Acsdfgfgtive", description="Device secret key"),
     *                   @OA\Property(property="device_model", type="string", example="Active vcxzv", description="Device model"),
     *                   @OA\Property(property="rfid", type="string", example="Yes", description="Device rfid")
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
            // Device Creation
            $device = new Device();
            $device->name = $request->name;
            $device->device_vendor_id = $request->device_vendor_id;
            $device->status = $request->status;
            $device->api_key = $request->api_key;
            $device->device_type = $request->device_type;

            if ('' !== $request->rfid) {
                $device->rfid = $request->rfid;
            }
            if ('' !== $request->device_sim) {
                $device->device_sim = $request->device_sim;
            }
            if ('' !== $request->secret_key) {
                $device->secret_key = $request->secret_key;
            }
            if ('' !== $request->device_model) {
                $device->device_model = $request->device_model;
            }
            $device->save();

            DB::commit();

            return ResponseHelper::success(new DeviceResource($device), 'Device created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/device/{id}",
     *     summary="Show a specific Device",
     *     operationId="showDevice",
     *     tags={"Devices"},
     *     description="Fetch details of a Device by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Device to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Device details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Device data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/DeviceResource"),
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
     *             @OA\Property(property="message", type="string", example="Invalid Device ID"),
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
            $device = Device::with('vendor')->findOrFail($id);
            if ( ! $device) {
                return ResponseHelper::error('Device not found');
            }

            return ResponseHelper::success(new DeviceResource($device), 'Device data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/device/{id}",
     *     summary="Update a specific Device",
     *     operationId="updateDevice",
     *     tags={"Devices"},
     *     description="Update the details of a Device by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Device to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Device data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="My company", description="Device name"),
     *                  @OA\Property(property="device_vendor_id", type="string", example="safsdfsf", description="Device Vendor id"),
     *                  @OA\Property(property="api_key", type="string", example="Mydsfsdfds", description="Device api key"),
     *                  @OA\Property(property="device_type", type="string", example="My company", description="Device type"),
     *                  @OA\Property(property="status", type="string", example="Active", description="Device status"),
     *                  @OA\Property(property="device_sim", type="string", example="No", description="Device Sim"),
     *                  @OA\Property(property="secret_key", type="string", example="Acsdfgfgtive", description="Device secret key"),
     *                  @OA\Property(property="device_model", type="string", example="Active vcxzv", description="Device model"),
     *                  @OA\Property(property="rfid", type="string", example="Yes", description="Device rfid")
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Device updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/DeviceResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Device not found"
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
                    Rule::unique('devices', 'name')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
                ],
                'device_vendor_id' => 'required|string|exists:device_vendors,uuid',
                'status' => 'required|string|in:Active,Inactive',
                'api_key' => [
                    'required',
                    'string',
                    'max:155',
                    Rule::unique('devices', 'api_key')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
                ],
                'device_type' => 'required|string',
                'device_sim' => 'nullable|string|in:Yes,No',
                'secret_key' => 'nullable|string',
                'device_model' => 'nullable|string',
                'rfid' => 'nullable|string|in:Yes,No',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $device = Device::findOrFail($id);
            if ( ! $device) {
                return ResponseHelper::error('Device not found');
            }

            $device->update($request->all());
            DB::commit();

            return ResponseHelper::success(new DeviceResource($device), 'Device updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/device/{id}",
     *     summary="Delete a specific Device",
     *     operationId="deleteDevice",
     *     tags={"Devices"},
     *     description="Delete a Device by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Device to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Device deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Device not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $device = Device::findOrFail($id);
            if ( ! $device) {
                return ResponseHelper::error('Device not found');
            }

            $device->delete();
            DB::commit();

            return ResponseHelper::success(null,'Device deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
