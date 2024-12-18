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
     *     summary="Get list of all Hospitals",
     *     operationId="getAllHospitals",
     *     tags={"Hospitals"},
     *     description="Fetch all hospital data.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hospital data retrieved successfully",
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
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred on the server"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
     *     )
     * )
     *
     * Get all Hospitals
     */
    public function index(): JsonResponse
    {
        try {
            $hospitals = Hospital::with('doctor')->where('status', 'Active')->get();

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
     *     description="Create a new hospital with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "address_line_1", "city", "state", "country", "postal_code", "phone", "email", "status"},
     *
     *                 @OA\Property(property="name", type="string", example="City Hospital", description="The name of the hospital"),
     *                 @OA\Property(property="address_line_1", type="string", example="123 Main Street", description="Primary address line"),
     *                 @OA\Property(property="address_line_2", type="string", example="Suite 101", description="Secondary address line (optional)"),
     *                 @OA\Property(property="city", type="string", example="New York", description="City of the hospital"),
     *                 @OA\Property(property="state", type="string", example="NY", description="State of the hospital"),
     *                 @OA\Property(property="country", type="string", example="USA", description="Country of the hospital"),
     *                 @OA\Property(property="postal_code", type="string", example="10001", description="Postal code for the hospital address"),
     *                 @OA\Property(property="phone", type="string", example="1231231231", description="Contact phone number"),
     *                 @OA\Property(property="email", type="string", example="contact@cityhospital.com", description="Contact email address"),
     *                 @OA\Property(property="description", type="string", example="A leading healthcare provider in the city", description="Description of the hospital (optional)"),
     *                 @OA\Property(property="status", type="string", example="Active", description="Status of the hospital. Can be 'Active' or 'Inactive'"),
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
     *             @OA\Property(property="statusCode", type="integer", example=201),
     *             @OA\Property(property="data", ref="#/components/schemas/HospitalResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid input data"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
     *     )
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
            if ('' !== $request->status) {
                $hospital->status = $request->status;
            }
            $hospital->phone = $request->phone;
            $hospital->address_line_1 = $request->address_line_1;
            $hospital->address_line_2 = $request->address_line_2;
            $hospital->city = $request->city;
            $hospital->state = $request->state;
            $hospital->country = $request->country;
            $hospital->postal_code = $request->postal_code;

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
     *     description="Update the details of a Hospital by their unique ID.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Unique ID of the Hospital to update",
     *
     *         @OA\Schema(
     *             type="string",
     *             example="9d445a1c-cee5-4a68-b729-9edf8df71d87"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated data for the Hospital",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="City General Hospital", description="Name of the hospital"),
     *                  @OA\Property(property="location", type="string", example="New York", description="Location of the hospital"),
     *                  @OA\Property(property="phone", type="string", example="1231231234", description="Hospital's contact phone number"),
     *                  @OA\Property(property="email", type="string", example="hospital@example.com", description="Contact email for the hospital"),
     *                  @OA\Property(property="status", type="string", example="Active", description="Hospital status (Active/Inactive)"),
     *                  @OA\Property(property="address_line_1", type="string", example="123 Main Street", description="Primary address line"),
     *                  @OA\Property(property="address_line_2", type="string", example="Suite 200", description="Secondary address line (optional)"),
     *                  @OA\Property(property="city", type="string", example="New York", description="City where the hospital is located"),
     *                  @OA\Property(property="state", type="string", example="NY", description="State where the hospital is located"),
     *                  @OA\Property(property="country", type="string", example="USA", description="Country where the hospital is located"),
     *                  @OA\Property(property="postal_code", type="string", example="10001", description="Postal code for the hospital"),
     *                  @OA\Property(property="description", type="string", example="A well-established hospital providing general healthcare services", description="Additional details about the hospital")
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Hospital updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Hospital updated successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/HospitalResource"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Hospital not found - the provided ID does not match any existing hospital",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Hospital not found"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - invalid input data",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Validation error, missing required fields"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error - unexpected issue while updating the hospital",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An error occurred on the server"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
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
                    Rule::unique('hospitals', 'name')->ignore($id, 'uuid'), // Ensures name is unique but ignores the current device
                ],
                'status' => 'required|string|in:Active,Inactive',
                'address_line_1' => 'required|string',
                'address_line_2' => 'nullable|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'country' => 'required|string',
                'postal_code' => 'required|string|min:5',
                'phone' => 'required|string|max:15',
                'email' => 'required|email|unique:hospitals,email,' . $id . ',uuid',
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

            return ResponseHelper::success(null, 'Hospital deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
