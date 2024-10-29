<?php

declare(strict_types=1);

namespace Modules\V1\User\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Modules\V1\User\Requests\UserUpdateRequest;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permissions:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store', 'show']]);
        $this->middleware('check.permissions:user-create', ['only' => ['create', 'store']]);
        $this->middleware('check.permissions:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('check.permissions:user-delete', ['only' => ['destroy']]);
    }

    /**
     * @OA\Get(
     *     path="/user/list",
     *     summary="List all users",
     *     description="Retrieve a list of all users with their roles and permissions",
     *     operationId="listUsers",
     *     tags={"User"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function list(): \Illuminate\Http\JsonResponse
    {
        try {
            $users = User::with('roles.permissions')->get();

            return ResponseHelper::success(UserResource::collection($users));
        } catch (Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/user/show/{id}",
     *     summary="Show user profile",
     *     description="Display the user's name and job title in the profile",
     *     operationId="showUserProfile",
     *     tags={"User"},
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=false,
     *          description="ID of the user to show",
     *
     *          @OA\Schema(
     *              type="string",
     *              example=1
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Profile data show successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=204),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     *      security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            if ('' !== $id) {
                $userData = User::findOrFail($id);
                if ( ! $userData) {
                    return ResponseHelper::error('User not found', 404);
                }
                $userData = $userData->load('roles.permissions');
            } else {
                $userData = Auth::user()->load('roles.permissions');
            }
            $userData = $userData->get();

            return ResponseHelper::success(UserResource::collection($userData));
        } catch (Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/user/update/{id}",
     *     summary="Update user profile",
     *     description="Updates the user's Profile information",
     *     operationId="updateUserProfile",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *      ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Profile updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=204),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     *      security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        DB::beginTransaction(); // Start the transaction
        try {
            // Initialize validated data
            $validatedData = $request->validated();
            //$user = auth()->user();
            $user = User::findOrFail($id);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Get the uploaded file
                $file = $request->file('profile_image');

                // Define a storage path (e.g., 'public/profile_images')
                $filePath = $file->store('profile_images', 'public/user_profile');

                // Add the file path to the validated data
                $validatedData['profile_image'] = $filePath;
            }
            //dd($validatedData);
            $user->update($validatedData);
            // Update roles if provided
            if ($request->has('roles')) {
                // Retrieve role IDs based on role names
                $roleNames = $request->input('roles');
                $roles = Role::where('name', $roleNames)->pluck('uuid'); // Adjust 'id' if your primary key is different
                $user->roles()->sync($roles); // Sync the roles
            }
            DB::commit(); // Commit the transaction

            return ResponseHelper::success(new UserResource($user), 'Profile updated successfully');
        } catch (Exception $e) {
            DB::rollBack(); // Roll back the transaction

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/user/change-password",
     *     summary="Change User Password",
     *     description="Change the user's password.",
     *     operationId="changePassword",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Password change request data",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="current_password", type="string", description="Current password", example="old_password"),
     *                 @OA\Property(property="new_password", type="string", description="New password", example="new_password"),
     *                 @OA\Property(property="new_password_confirmation", type="string", description="Confirm new password", example="new_password"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Password changed successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *         )
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     * )
     */
    public function changePassword(Request $request)
    {
        DB::beginTransaction(); // Start the transaction
        try {
            $user = auth()->user();

            $request->validate([
                'current_password' => 'required|string',
                //'new_password' => 'required|string|min:8|confirmed',
                'new_password' => ['required', 'confirmed', Password::defaults()],
            ]);

            if ( ! Hash::check($request->input('current_password'), $user->password)) {
                return ResponseHelper::error('Invalid current password', 402);
            }

            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);

            DB::commit(); // Commit the transaction

            return ResponseHelper::success('Password changed successfully');
        } catch (Exception $e) {
            DB::rollBack(); // Roll back the transaction

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/user/delete/{id}",
     *     summary="Delete User",
     *     description="Delete a user by ID.",
     *     operationId="deleteUser",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *
     *         @OA\Schema(
     *             type="string",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="User deleted successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=404, ref="#/components/responses/404"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction(); // Start the transaction
        try {
            $user = User::findOrFail($id);
            if ( ! $user) {
                return ResponseHelper::error('User not found', 404);
            }
            $user->delete();
            DB::commit(); // Commit the transaction

            return ResponseHelper::success(null, 'User deleted successfully');
        } catch (Exception $e) {
            DB::rollBack(); // Roll back the transaction

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}
