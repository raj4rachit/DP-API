<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Patient\Models\Patient;
use Modules\V1\Patient\Models\PatientMedicalHistory;
use Modules\V1\Patient\Resources\PatientResource;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class PatientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/patient/",
     *     summary="List all patients",
     *     operationId="listPatients",
     *     tags={"Patients"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of patients",
     *
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PatientResource"))
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        $patients = Patient::with('user', 'medicalHistories')->get();
        return ResponseHelper::success(data: PatientResource::collection($patients), message: 'Patients data getting successfully. ');
    }

    /**
     * @OA\Post(
     *     path="/patient/",
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
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => 'required|string|in:Male,Female,Other',
                'dob' => 'required|date',
                'address' => 'required|string',
                'mobile_no' => 'nullable|string|max:20',
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'id_type' => 'required|string',
                'id_number' => 'required|string',
                'arn_number' => 'required|string',
                'marital_status' => 'required|string|in:Single,Married,Divorced,Widowed',
                'primary_phone' => 'required|string|max:20',
                'secondary_phone' => 'nullable|string|max:20',
                'home_phone' => 'nullable|string|max:20',
                'work_phone' => 'nullable|string|max:20',
                'languages' => 'required|array',
                'medical_aid'  => 'required|string',
                'race' => 'nullable|string',
                'ethnicity' => 'nullable|string',
                'mrn_number' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // User Creation
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->user_type = 'patient';
            $user->password = Hash::make('123456789');
            if($request->mobile_no != ''){
                $user->mobile_no = $request->mobile_no;
            }
            $user->save();
            $roleNames = 'Patient';
            // Retrieve role IDs based on role names
            $roles = Role::where('name', $roleNames)->pluck('uuid'); // Adjust 'id' if your primary key is different
            $user->roles()->sync($roles); // Sync the roles
            // send email verification mail
            $user->sendEmailVerificationNotification();

            // Patient Creation
            $patient = new Patient();
            $patient->user_id = $user->uuid;
            $patient->gender = $request->gender;
            $patient->dob = $request->dob;
            $patient->address = $request->address;
            $patient->id_type = $request->id_type;
            $patient->id_number = $request->id_number;
            $patient->arn_number = $request->arn_number;
            $patient->marital_status = $request->marital_status;
            $patient->primary_phone = $request->primary_phone;
            if($request->home_phone != ''){
                $patient->home_phone = $request->home_phone;
            }
            if($request->work_phone != ''){
                $patient->work_phone = $request->work_phone;
            }
            if($request->secondary_phone != ''){
                $patient->secondary_phone = $request->secondary_phone;
            }
            if($request->work_phone != ''){
                $patient->work_phone = $request->work_phone;
            }
            $patient->languages = $request->languages;
            $patient->save();

            // Patient Medical History
            $patientMedicalHistory = new PatientMedicalHistory();
            $patientMedicalHistory->patient_id = $patient->uuid;
            $patientMedicalHistory->medical_aid = $request->medical_aid;
            if($request->race != ''){
                $patientMedicalHistory->race = $request->race;
            }
            if($request->ethnicity != ''){
                $patientMedicalHistory->ethnicity = $request->ethnicity;
            }
            if($request->mrn_number != ''){
                $patientMedicalHistory->mrn_number = $request->mrn_number;
            }
            $patientMedicalHistory->save();

            DB::commit();
            return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient created successfully');
        } catch (Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/patient/{id}",
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
    public function show($id): JsonResponse
    {
        $patient = Patient::with('user', 'medicalHistories')->findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Patient not found');
        }

        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient data fetched successfully');
    }

    /**
     * @OA\Put(
     *     path="/patient/{id}",
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
    public function update(Request $request, $id): JsonResponse
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

        $patient = Patient::findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Patient not found');
        }

        $patient->update($request->all());

        return ResponseHelper::success(data: new PatientResource($patient), message: 'Patient updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/patient/{id}",
     *     summary="Delete a specific patient",
     *     operationId="deletePatient",
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
     *         response=204,
     *         description="Patient deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Patient not found');
        }

        $patient->delete();

        return ResponseHelper::success(message: 'Patient deleted successfully');

        return response()->json(null, 204);
    }
}
