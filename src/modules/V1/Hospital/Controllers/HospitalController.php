<?php

declare(strict_types=1);

namespace Modules\V1\Hospital\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\V1\Hospital\Models\Hospital;
use Modules\V1\Hospital\Requests\HospitalCreateRequest;
use Modules\V1\Hospital\Resources\HospitalResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class HospitalController extends Controller
{
    /**
     * @OA\Get(
     *     path="/hospital",
     *     summary="Get list of all devices",
     *     tags={"Hospitals"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hospital data getting successfully",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/HospitalResource")
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
            $hospitals = Hospital::with('doctor')->get();

            return ResponseHelper::success(HospitalResource::collection($hospitals), 'Hospital data getting successfully. ');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/hospital",
     *     summary="Create a new Hospital",
     *     operationId="createHospital",
     *     tags={"Hospitals"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Hospital with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                  @OA\Property(property="name", type="string", example="test name"),
     *                  @OA\Property(property="location", type="string", example="JohnDoe"),
     *                  @OA\Property(property="phone", type="string", example="1231231231"),
     *                  @OA\Property(property="email",type="string", example="hos@gmail.com"),
     *                  @OA\Property(property="description",type="string", example="Asddfdsfererer"),
     *                  @OA\Property(property="status",type="string", example="Active"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Hospital created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Hospital created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/HospitalResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(HospitalCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Hospital Creation
            $hospital = new Hospital();
            $hospital->name = $request->name;
            $hospital->email = $request->email;
            if($request->status != ''){
                $hospital->status = $request->status;
            }
            $hospital->phone = $request->phone;
            $hospital->location = $request->location;

            if ('' !== $request->description) {
                $hospital->description = $request->description;
            }
            $hospital->save();

            DB::commit();

            return ResponseHelper::success(new HospitalResource($hospital), 'Hospital created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/hospital/{id}",
     *     summary="Show a specific Hospital",
     *     operationId="showHospital",
     *     tags={"Hospitals"},
     *     description="Fetch details of a Hospital by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Hospital to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hospital details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Hospital data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/HospitalResource"),
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
     *             @OA\Property(property="message", type="string", example="Invalid Hospital ID"),
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
            $hospital = Hospital::findOrFail($id);
            if ( ! $hospital) {
                return ResponseHelper::error('Hospital not found');
            }

            return ResponseHelper::success(new HospitalResource($hospital), 'Hospital data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/hospital/{id}",
     *     summary="Update a specific Hospital",
     *     operationId="updateHospital",
     *     tags={"Hospitals"},
     *     description="Update the details of a Hospital by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Hospital to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Hospital data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="My company", description="Hospital name"),
     *                  @OA\Property(property="device_vendor_id", type="string", example="safsdfsf", description="Hospital Vendor id"),
     *                  @OA\Property(property="api_key", type="string", example="Mydsfsdfds", description="Hospital api key"),
     *                  @OA\Property(property="device_type", type="string", example="My company", description="Hospital type"),
     *                  @OA\Property(property="status", type="string", example="Active", description="Hospital status"),
     *                  @OA\Property(property="device_sim", type="string", example="No", description="Hospital Sim"),
     *                  @OA\Property(property="secret_key", type="string", example="Acsdfgfgtive", description="Hospital secret key"),
     *                  @OA\Property(property="device_model", type="string", example="Active vcxzv", description="Hospital model"),
     *                  @OA\Property(property="rfid", type="string", example="Yes", description="Hospital rfid")
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hospital updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/HospitalResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Hospital not found"
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
                    Rule::unique('hospitalS', 'name')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
                ],
                'status' => 'required|string|in:Active,Inactive',
                'location' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'required|email|unique:hospitals,email,' . $id.',uuid',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $hospital = Hospital::findOrFail($id);
            if ( ! $hospital) {
                return ResponseHelper::error('Hospital not found');
            }

            $hospital->update($request->all());
            DB::commit();

            return ResponseHelper::success(new HospitalResource($hospital), 'Hospital updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/hospital/{id}",
     *     summary="Delete a specific Hospital",
     *     operationId="deleteHospital",
     *     tags={"Hospitals"},
     *     description="Delete a Hospital by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Hospital to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Hospital deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hospital not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $hospital = Hospital::findOrFail($id);
            if ( ! $hospital) {
                return ResponseHelper::error('Hospital not found');
            }

            $hospital->delete();
            DB::commit();

            return ResponseHelper::success(null,'Hospital deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
