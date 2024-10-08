<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Patient\Models\Patient;
use Modules\V1\User\Models\User;
use Modules\V1\Patient\Resources\PatientResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class PatientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/patients/",
     *     summary="List all patients",
     *     operationId="listPatients",
     *     tags={"Patients"},
     *     @OA\Response(
     *         response=200,
     *         description="List of patients",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PatientResource"))
     *     ),
     * )
     */
    public function index()
    {
        return ResponseHelper::success(data: PatientResource::collection(Patient::all()), message: 'Patients data getting successfully. ');
    }

    /**
     * @OA\Post(
     *     path="/patients/",
     *     summary="Create a new patient",
     *     operationId="createPatient",
     *     tags={"Patients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatientCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Patient created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     * )
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'address' => 'required|string',
            'mobile_no' => 'nullable|string|max:20',
            'arn_number' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient = Patient::create($request->all());
        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient created successfully');
    }

    /**
     * @OA\Get(
     *     path="/patients/{id}",
     *     summary="Show a specific patient",
     *     operationId="showPatient",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient details",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function show($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return ResponseHelper::error('Patient not found');
        }
        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient data fetched successfully');
    }

    /**
     * @OA\Put(
     *     path="/patients/{id}",
     *     summary="Update a specific patient",
     *     operationId="updatePatient",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatientUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string',
            'dob' => 'sometimes|required|date',
            'address' => 'sometimes|required|string',
            'mobile_no' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient = Patient::find($id);
        if (!$patient) {
            return ResponseHelper::error('Patient not found');
        }

        $patient->update($request->all());
        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/patients/{id}",
     *     summary="Delete a specific patient",
     *     operationId="deletePatient",
     *     tags={"Patients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Patient deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return ResponseHelper::error('Patient not found');
        }

        $patient->delete();
        return ResponseHelper::success(message: 'Patient deleted successfully');
        return response()->json(null, 204);
    }
}

