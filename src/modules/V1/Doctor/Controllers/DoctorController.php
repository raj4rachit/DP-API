<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\Doctor\Models\DoctorSpecialization;
use Modules\V1\Doctor\Models\SpecializationDoctor;
use Modules\V1\Doctor\Requests\DoctorCreateRequest;
use Modules\V1\Doctor\Requests\DoctorSpecializationCreateRequest;
use Modules\V1\Doctor\Resources\DoctorResource;
use Modules\V1\Doctor\Resources\DoctorSpecializationResource;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class DoctorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/doctor",
     *     summary="List all Doctors",
     *     operationId="listDoctors",
     *     tags={"Doctors"},
     *     description="Retrieve a list of all Doctors.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of Doctors",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Doctors data retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/DoctorResource")
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
        $doctors = Doctor::with(['user', 'hospital', 'specializations', 'patients', 'subscription'])->get();

        return ResponseHelper::success(DoctorResource::collection($doctors), 'Doctors data getting successfully.');
    }

    /**
     * @OA\Post(
     *     path="/doctor",
     *     summary="Create a new Doctor",
     *     operationId="createDoctor",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Doctor with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="first_name", type="string", description="Doctor's first name"),
     *                 @OA\Property(property="last_name", type="string", description="Doctor's last name"),
     *                 @OA\Property(property="gender", type="string", description="Doctor's gender"),
     *                 @OA\Property(property="dob", type="string", format="date", description="Date of birth (YYYY-MM-DD)"),
     *                 @OA\Property(property="clinic_address", type="string", description="Doctor's clinic address"),
     *                 @OA\Property(property="mobile_no", type="string", description="Doctor's mobile number"),
     *                 @OA\Property(property="email", type="string", description="Doctor's email address"),
     *                 @OA\Property(property="hospital_id ", type="string", description="hospital_id "),
     *                 @OA\Property(property="marital_status", type="string", description="Marital status"),
     *                 @OA\Property(
     *                     property="specialization",
     *                     type="array",
     *
     *                     @OA\Items(type="string"),
     *                     description="specialization by the doctor"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Doctor created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Doctor created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/DoctorResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(DoctorCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // User Creation
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->user_type = 'doctor';
            $user->password = Hash::make('123456789');
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->marital_status = $request->marital_status;
            if ('' !== $request->mobile_no) {
                $user->mobile_no = $request->mobile_no;
            }
            $user->save();
            $roleNames = 'Doctor';
            // Retrieve role IDs based on role names
            $roles = Role::where('name', $roleNames)->pluck('uuid'); // Adjust 'id' if your primary key is different
            $user->roles()->sync($roles); // Sync the roles
            // send email verification mail
            $user->sendEmailVerificationNotification();

            // Doctor Creation
            $doctor = new Doctor();
            $doctor->user_id = $user->uuid;
            $doctor->clinic_address = $request->clinic_address;
            $doctor->hospital_id = $request->hospital_id;
            if ('' !== $request->contact_phone) {
                $doctor->contact_phone = $request->contact_phone;
            }
            $doctor->save();

            // Doctor Specialization
            if ('' !== $request->specialization) {
                foreach ($request->specialization as $value) {
                    $doctorSpecialization = new SpecializationDoctor();
                    $doctorSpecialization->doctor_id = $doctor->uuid;
                    $doctorSpecialization->specialization_id = $value;
                    $doctorSpecialization->save();
                }

            }

            DB::commit();

            return ResponseHelper::success(new DoctorResource($doctor), 'Doctor created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/doctor/{id}",
     *     summary="Show a specific Doctor",
     *     operationId="showDoctor",
     *     tags={"Doctors"},
     *     description="Fetch details of a Doctor by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Doctor to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Doctor details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Profile fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/DoctorResource"),
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
     *             @OA\Property(property="message", type="string", example="Invalid Doctor ID"),
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
        $doctor = Doctor::with(['user', 'hospital', 'specializations', 'patients'])->findOrFail($id);
        if ( ! $doctor) {
            return ResponseHelper::error('Doctor not found');
        }

        return ResponseHelper::success(new DoctorResource($doctor), 'Doctor data fetched successfully');
    }

    /**
     * @OA\Put(
     *     path="/doctor/{id}",
     *     summary="Update a specific Doctor",
     *     operationId="updateDoctor",
     *     tags={"Doctors"},
     *     description="Update the details of a Doctor by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Doctor to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Doctor data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="first_name", type="string", description="Doctor's first name"),
     *                  @OA\Property(property="last_name", type="string", description="Doctor's last name"),
     *                  @OA\Property(property="gender", type="string", description="Doctor's gender"),
     *                  @OA\Property(property="dob", type="string", format="date", description="Date of birth (YYYY-MM-DD)"),
     *                  @OA\Property(property="clinic_address", type="string", description="Doctor's clinic address"),
     *                  @OA\Property(property="mobile_no", type="string", description="Doctor's mobile number"),
     *                  @OA\Property(property="email", type="string", description="Doctor's email address"),
     *                  @OA\Property(property="hospital_id ", type="string", description="hospital_id "),
     *                  @OA\Property(property="marital_status", type="string", description="Marital status"),
     *                  @OA\Property(
     *                      property="specialization",
     *                      type="array",
     *
     *                      @OA\Items(type="string"),
     *                      description="specialization by the doctor"
     *                  )
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Doctor updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/DoctorResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Doctor not found"
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
            'clinic_address' => 'required|string',
            'mobile_no' => 'nullable|string|max:20',
            'marital_status' => 'required|string|in:Single,Married,Divorced,Widowed',
            'contact_phone' => 'nullable|string|max:20',
            'hospital_id' => 'required|string|exists:hospitals,uuid',
            'specialization' => 'required|array|exists:doctor_specializations,uuid',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor = Doctor::findOrFail($id);
        if ( ! $doctor) {
            return ResponseHelper::error('Doctor not found');
        }
        $doctor->update($request->all());

        $doctorSpecializations = SpecializationDoctor::where('doctor_id', $id)->get();
        if ( ! $doctorSpecializations->isEmpty()) {
            SpecializationDoctor::where('doctor_id', $id)->delete();
        }

        // Doctor Specialization
        if ('' !== $request->specialization) {
            foreach ($request->specialization as $value) {
                $doctorSpecialization = new SpecializationDoctor();
                $doctorSpecialization->doctor_id = $doctor->uuid;
                $doctorSpecialization->specialization_id = $value;
                $doctorSpecialization->save();
            }

        }

        return ResponseHelper::success(new DoctorResource($doctor), 'Doctor updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/doctor/{id}",
     *     summary="Delete a specific Doctor",
     *     operationId="deleteDoctor",
     *     tags={"Doctors"},
     *     description="Delete a Doctor by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Doctor to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Doctor deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Doctor not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        if ( ! $doctor) {
            return ResponseHelper::error('Doctor not found');
        }

        $doctor->delete();

        return ResponseHelper::success(null, 'Doctor deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/doctor/specialization",
     *     summary="List all Doctor Specializations",
     *     operationId="listSpecializations",
     *     tags={"Doctors"},
     *     description="Retrieve a list of all Doctor Specializations.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of Doctor Specializations",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Doctor Specializations data retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/DoctorSpecializationResource")
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
    public function doctorSpecializations(): JsonResponse
    {
        $specializations = DoctorSpecialization::all();

        return ResponseHelper::success(DoctorSpecializationResource::collection($specializations), 'Doctor specializations data getting successfully.');
    }

    /**
     * @OA\Post(
     *     path="/doctor/specialization",
     *     summary="Create a new Doctor specialization",
     *     operationId="createDoctorSpecialization",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Doctor specialization with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="name", type="string", description="Doctor specialization's name"),
     *                  @OA\Property(property="code", type="string", description="Doctor specialization's code"),
     *                 @OA\Property(property="description", type="string", description="Doctor specialization's description")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Doctor specialization created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Doctor specialization created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/DoctorSpecializationResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function specializationStore(DoctorSpecializationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Doctor Specialization Creation
            $specialization = new DoctorSpecialization();
            $specialization->name = $request->name;
            $specialization->code = $request->code;
            $specialization->description = $request->description;

            $specialization->save();
            DB::commit();

            return ResponseHelper::success(new DoctorSpecializationResource($specialization), 'Doctor specialization created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/doctor/specialization/{id}",
     *     summary="Show a specific Doctor specialization",
     *     operationId="showDoctorSpecialization",
     *     tags={"Doctors"},
     *     description="Fetch details of a Doctor specialization by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Doctor specialization to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Doctor specialization details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="specialization fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/DoctorSpecializationResource"),
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
     *             @OA\Property(property="message", type="string", example="Invalid Doctor specialization ID"),
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
    public function specializationShow($id): JsonResponse
    {
        $doctorSpecialization = DoctorSpecialization::with('doctors')->findOrFail($id);
        if ( ! $doctorSpecialization) {
            return ResponseHelper::error('Doctor not found');
        }

        return ResponseHelper::success(new DoctorSpecializationResource($doctorSpecialization), 'Doctor specialization data fetched successfully');
    }

    /**
     * @OA\Put(
     *     path="/doctor/specialization/{id}",
     *     summary="Update a specific Doctor specialization",
     *     operationId="updateDoctorSpecialization",
     *     tags={"Doctors"},
     *     description="Update the details of a Doctor specialization by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Doctor specialization to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Doctor specialization data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", description="Doctor specialization's name"),
     *                   @OA\Property(property="code", type="string", description="Doctor specialization's code"),
     *                  @OA\Property(property="description", type="string", description="Doctor specialization's description")
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Doctor specialization updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/DoctorSpecializationResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Doctor specialization not found"
     *     )
     * )
     */
    public function specializationUpdate(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:doctor_specializations,name,' . $id . ',uuid',
            'description' => 'required|string|max:255',
            'code' => 'required|string|unique:doctor_specializations,code,' . $id . ',uuid',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $specialization = DoctorSpecialization::findOrFail($id);
        if ( ! $specialization) {
            return ResponseHelper::error('Doctor specialization not found');
        }

        $specialization->update($request->all());

        return ResponseHelper::success(new DoctorSpecializationResource($specialization), 'Doctor specialization updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/doctor/specialization/{id}",
     *     summary="Delete a specific Doctor specialization",
     *     operationId="deleteDoctorSpecialization",
     *     tags={"Doctors"},
     *     description="Delete a Doctor  specialization by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Doctor specialization to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Doctor specialization deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Doctor specialization not found"
     *     )
     * )
     */
    public function specializationDestroy($id)
    {
        $specialization = DoctorSpecialization::findOrFail($id);
        if ( ! $specialization) {
            return ResponseHelper::error('Doctor specialization not found');
        }

        $specialization->delete();

        return ResponseHelper::success(null, 'Doctor specialization deleted successfully');
    }
}
