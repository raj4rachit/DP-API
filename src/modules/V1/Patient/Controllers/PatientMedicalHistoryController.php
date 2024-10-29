<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Patient\Models\PatientMedicalHistory;
use Modules\V1\Patient\Resources\PatientResource;
use Shared\Helpers\ResponseHelper;

final class PatientMedicalHistoryController extends Controller
{
    public function store(Request $request): JsonResponse
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

        return ResponseHelper::success(new PatientResource($patient), 'Patient created successfully');
    }

    public function show($id)
    {
        $history = PatientMedicalHistory::findOrFail($id);
        if ( ! $history) {
            return ResponseHelper::error('Patient not found');
        }

        return ResponseHelper::success(new PatientResource($history), 'Patient data fetched successfully');
    }

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

        return ResponseHelper::success(new PatientResource($patient), 'Patient updated successfully');
    }
}
