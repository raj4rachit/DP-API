<?php

declare(strict_types=1);

namespace Modules\V1\Lab\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Lab\Models\Lab;
use Modules\V1\Lab\Requests\LabCreateRequest;
use Modules\V1\Lab\Resources\LabResource;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class LabController extends Controller
{
    /**
     * @OA\Get(
     *     path="/lab",
     *     summary="List all Labs",
     *     operationId="listLabs",
     *     tags={"Labs"},
     *     description="Retrieve a list of all Labs.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of Labs",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Labs data retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/LabResource")
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
        $labs = Lab::with('user', 'reports')->get();
        return ResponseHelper::success(LabResource::collection($labs), 'Labs data getting successfully. ');
    }

    /**
     * @OA\Post(
     *     path="/lab",
     *     summary="Create a new Lab",
     *     operationId="createLab",
     *     tags={"Labs"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Lab with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Lab's first name"),
     *                 @OA\Property(property="address", type="string", description="Lab's address"),
     *                 @OA\Property(property="phone", type="string", description="Lab's mobile number"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Lab created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lab created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/LabResource"),
     *         )
     *     ),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */

    public function store(LabCreateRequest $request): JsonResponse
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
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->marital_status = $request->marital_status;
            if($request->mobile_no != ''){
                $user->mobile_no = $request->mobile_no;
            }
            $user->save();
            $roleNames = 'Lab';
            // Retrieve role IDs based on role names
            $roles = Role::where('name', $roleNames)->pluck('uuid'); // Adjust 'id' if your primary key is different
            $user->roles()->sync($roles); // Sync the roles
            // send email verification mail
            $user->sendEmailVerificationNotification();

            // Lab Creation
            $patient = new Lab();
            $patient->user_id = $user->uuid;
            $patient->address = $request->address;
            $patient->id_type = $request->id_type;
            $patient->id_number = $request->id_number;
            $patient->arn_number = $request->arn_number;
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

            // Lab Medical History
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
            return ResponseHelper::success(new LabResource($patient), 'Lab created successfully');
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
     *         description="Lab details",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Profile fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/LabResource"),
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
        $patient = Lab::with('user', 'medicalHistories')->findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Lab not found');
        }

        return ResponseHelper::success(new LabResource($patient), 'Lab data fetched successfully');
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
     *                  @OA\Property(property="first_name", type="string", description="Lab's first name"),
     *                  @OA\Property(property="last_name", type="string", description="Lab's last name"),
     *                  @OA\Property(property="gender", type="string", description="Lab's gender"),
     *                  @OA\Property(property="dob", type="string", format="date", description="Date of birth (YYYY-MM-DD)"),
     *                  @OA\Property(property="address", type="string", description="Lab's address"),
     *                  @OA\Property(property="mobile_no", type="string", description="Lab's mobile number"),
     *                  @OA\Property(property="email", type="string", description="Lab's email address"),
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
     *         description="Lab updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LabResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Lab not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'dob' => 'required|date',
            'address' => 'required|string',
            'mobile_no' => 'nullable|string|max:20',
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

        $patient = Lab::findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Lab not found');
        }

        $patient->update($request->all());

        return ResponseHelper::success(new LabResource($patient), 'Lab updated successfully');
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
     *         description="Lab deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lab not found"
     *     )
     * )
     */

    public function destroy($id)
    {
        $patient = Lab::findOrFail($id);
        if ( ! $patient) {
            return ResponseHelper::error('Lab not found');
        }

        $patient->delete();
        return ResponseHelper::success(null,'Lab deleted successfully');
    }
}
