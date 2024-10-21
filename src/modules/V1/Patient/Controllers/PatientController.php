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
use Modules\V1\Patient\Requests\PatientCreateRequest;
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
     *     path="/patient",
     *     summary="List all patients",
     *     operationId="listPatients",
     *     tags={"Patients"},
     *     description="Retrieve a list of all patients.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of patients",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Patients data retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/PatientResource")
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
        $patients = Patient::with('user', 'medicalHistories')->get();
        return ResponseHelper::success(data: PatientResource::collection($patients), message: 'Patients data getting successfully. ');
    }

    /**
     * @OA\Post(
     *     path="/patient",
     *     summary="Create a new patient",
     *     operationId="createPatient",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new patient with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="first_name", type="string", description="Patient's first name"),
     *                 @OA\Property(property="last_name", type="string", description="Patient's last name"),
     *                 @OA\Property(property="gender", type="string", description="Patient's gender"),
     *                 @OA\Property(property="dob", type="string", format="date", description="Date of birth (YYYY-MM-DD)"),
     *                 @OA\Property(property="address", type="string", description="Patient's address"),
     *                 @OA\Property(property="mobile_no", type="string", description="Patient's mobile number"),
     *                 @OA\Property(property="email", type="string", description="Patient's email address"),
     *                 @OA\Property(property="id_type", type="string", description="ID type"),
     *                 @OA\Property(property="id_number", type="string", description="ID number"),
     *                 @OA\Property(property="arn_number", type="string", description="ARN number"),
     *                 @OA\Property(property="marital_status", type="string", description="Marital status"),
     *                 @OA\Property(property="primary_phone", type="string", description="Primary phone number"),
     *                 @OA\Property(property="secondary_phone", type="string", description="Secondary phone number"),
     *                 @OA\Property(property="home_phone", type="string", description="Home phone number"),
     *                 @OA\Property(property="work_phone", type="string", description="Work phone number"),
     *                 @OA\Property(
     *                     property="languages",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     description="Languages spoken by the patient"
     *                 ),
     *                 @OA\Property(property="medical_aid", type="string", description="Medical aid"),
     *                 @OA\Property(property="race", type="string", description="Race"),
     *                 @OA\Property(property="ethnicity", type="string", description="Ethnicity"),
     *                 @OA\Property(property="mrn_number", type="string", description="MRN number")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Patient created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Patient created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/PatientResource"),
     *         )
     *     ),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */

    public function store(PatientCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
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
     *     description="Fetch details of a patient by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to show",
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Patient details",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Profile fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/PatientResource"),
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized access"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid patient ID"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
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
     *     description="Update the details of a patient by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to update",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated patient data",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="first_name", type="string", description="Patient's first name"),
     *                  @OA\Property(property="last_name", type="string", description="Patient's last name"),
     *                  @OA\Property(property="gender", type="string", description="Patient's gender"),
     *                  @OA\Property(property="dob", type="string", format="date", description="Date of birth (YYYY-MM-DD)"),
     *                  @OA\Property(property="address", type="string", description="Patient's address"),
     *                  @OA\Property(property="mobile_no", type="string", description="Patient's mobile number"),
     *                  @OA\Property(property="email", type="string", description="Patient's email address"),
     *                  @OA\Property(property="id_type", type="string", description="ID type"),
     *                  @OA\Property(property="id_number", type="string", description="ID number"),
     *                  @OA\Property(property="arn_number", type="string", description="ARN number"),
     *                  @OA\Property(property="marital_status", type="string", description="Marital status"),
     *                  @OA\Property(property="primary_phone", type="string", description="Primary phone number"),
     *                  @OA\Property(property="secondary_phone", type="string", description="Secondary phone number"),
     *                  @OA\Property(property="home_phone", type="string", description="Home phone number"),
     *                  @OA\Property(property="work_phone", type="string", description="Work phone number"),
     *                  @OA\Property(
     *                      property="languages",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                      description="Languages spoken by the patient"
     *                  ),
     *                  @OA\Property(property="medical_aid", type="string", description="Medical aid"),
     *                  @OA\Property(property="race", type="string", description="Race"),
     *                  @OA\Property(property="ethnicity", type="string", description="Ethnicity"),
     *                  @OA\Property(property="mrn_number", type="string", description="MRN number")
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Patient updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     )
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
     *     description="Delete a patient by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the patient to be deleted",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Patient deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     )
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
