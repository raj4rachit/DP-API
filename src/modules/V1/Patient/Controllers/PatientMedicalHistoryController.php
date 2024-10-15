<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Patient\Models\Patient;
use Modules\V1\Patient\Models\PatientMedicalHistory;
use Modules\V1\Patient\Resources\PatientResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class PatientMedicalHistoryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/patients/",
     *     summary="Create a new patient",
     *     operationId="createPatient",
     *     tags={"Patients"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientCreateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Patient created successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     * )
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,uuid',
            'medical_aid' => 'nullable|string',
            'race' => 'nullable|string',
            'ethnicity' => 'nullable|string',
            'mrn_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient = PatientMedicalHistory::create($request->all());

        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient created successfully');
    }

    /**
     * @OA\Get(
     *     path="/patients/{id}",
     *     summary="Show a specific patient",
     *     operationId="showPatient",
     *     tags={"Patients"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Patient details",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function show($id)
    {
        $history = PatientMedicalHistory::findOrFail($id);
        if ( ! $history) {
            return ResponseHelper::error('Patient not found');
        }

        return ResponseHelper::success(data: new PatientResource($history), message: 'Patient data fetched successfully');
    }

    /**
     * @OA\Put(
     *     path="/patients/{id}",
     *     summary="Update a specific patient",
     *     operationId="updatePatient",
     *     tags={"Patients"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientUpdateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Patient updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medical_aid' => 'nullable|string',
            'race' => 'nullable|string',
            'ethnicity' => 'nullable|string',
            'mrn_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient = PatientMedicalHistory::findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Patient not found');
        }

        $patient->update($request->all());

        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient updated successfully');
    }
}
