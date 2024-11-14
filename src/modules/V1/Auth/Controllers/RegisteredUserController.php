<?php

declare(strict_types=1);

namespace Modules\V1\Auth\Controllers;

use App\Http\Controllers\V1\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\V1\Auth\Requests\RegisterRequest;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\Lab\Models\Lab;
use Modules\V1\Lab\Models\LabReport;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Shared\Helpers\ResponseHelper;
use Spatie\Permission\Models\Permission;

final class RegisteredUserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/auth/register",
     *      summary="Register a new user",
     *     tags={"Authentication"},
     *      description="Registers a new user with the provided information.",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="first_name", type="string", description="User's first name"),
     *                  @OA\Property(property="last_name", type="string", description="User's last name"),
     *                  @OA\Property(property="email", type="string", format="email", description="User's email"),
     *                  @OA\Property(property="password", type="string", format="password", description="User's password"),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Registration successful",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Registration successful"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="string", example="201"),
     *              @OA\Property(property="access-token", type="string", example="your_access_token"),
     *              @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *          )
     *      ),
     *
     *      @OA\Response(response=422, ref="#/components/responses/422"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     * )
     */
    public function store(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = new User();

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->user_type = $request->user_type ?? 'patient';
            $user->password = Hash::make($request->password);
            $user->save();
            $roleNames = 'User';
            $permissions = '';
            if ('doctor' === $request->user_type) {
                $roleNames = 'Doctor';
                $permissions = Permission::where('name', 'like', 'patient-%')->pluck('uuid')->toArray();
                $permissions = Permission::where('name', 'like', 'lab-%')->orWhere('name', 'like', 'report-%')->orWhere('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            }
            if ('lab' === $request->user_type) {
                $roleNames = 'Lab';
                $permissions = Permission::where('name', 'like', 'lab-%')->orWhere('name', 'like', 'report-%')->orWhere('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            }
            if ($request->has('roles')) {
                $roleNames = $request->input('roles');
            }
            // Retrieve role IDs based on role names
            $roles = Role::where('name', $roleNames)->first(); // Adjust 'id' if your primary key is different
            if($roles && $permissions){
                $roles->syncPermissions($permissions);
            }
            $user->roles()->sync($roles); // Sync the roles

            // send email verification mail
            $user->sendEmailVerificationNotification();

            // Doctor Creation
            if ('doctor' === $request->user_type) {
                $doctor = new Doctor();
                $doctor->user_id = $user->uuid;
                $doctor->email = $request->doctor_email_address;
                $doctor->address_line_1 = $request->address_line_1;
                $doctor->address_line_2 = $request->address_line_2;
                $doctor->city = $request->city;
                $doctor->state = $request->state;
                $doctor->country = $request->country;
                $doctor->postal_code = $request->postal_code;
                $doctor->contact_phone = $request->contact_phone;
                if ('' !== $request->hospital_id) {
                    $doctor->hospital_id = $request->hospital_id;
                }
                $doctor->save();
            }

            // Lab Creation
            if ('lab' === $request->user_type) {
                $lab = new Lab();
                $lab->user_id = $user->uuid;
                $lab->name = $request->name;
                $lab->address_line_1 = $request->address_line_1;
                $lab->address_line_2 = $request->address_line_2;
                $lab->city = $request->city;
                $lab->state = $request->state;
                $lab->country = $request->country;
                $lab->postal_code = $request->postal_code;
                $lab->phone = $request->phone;
                $lab->save();

                if (count($request->reports)) {
                    foreach ($request->reports as $report) {
                        $data = new LabReport();
                        $data->lab_id = $lab->uuid;
                        $data->report_id = $report;
                        $data->save();
                    }
                }
            }

            DB::commit();

            return ResponseHelper::success(null, 'Registration successful. Check your email for Verification.', 201);
        } catch (Exception $e) {
            DB::rollBack(); // Roll back the transaction

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}
