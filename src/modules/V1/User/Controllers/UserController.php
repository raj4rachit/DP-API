<?php

declare(strict_types=1);

namespace Modules\V1\User\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\V1\User\Models\User;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * @OA\Get(
     *     path="/user/show",
     *     summary="Show user profile",
     *     description="Display the user's name and job title in the profile",
     *     operationId="showUserProfile",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
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
    public function show(): \Illuminate\Http\JsonResponse
    {
        $userData = Auth::user()->with('role');
        //dd($userData->get());
        return ResponseHelper::success(new UserResource($userData));
    }

    /**
     * @OA\Put(
     *     path="/user",
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
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {

        // Validate request
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile_no' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'string', 'max:50'],
            'dob' => ['required', 'date'], // Validating as a proper date
            'profile_image' => ['nullable', 'image', 'max:2048'], // Must be an image (max 2MB)
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Get the uploaded file
            $file = $request->file('profile_image');

            // Define a storage path (e.g., 'public/profile_images')
            $filePath = $file->store('profile_images', 'public/user_profile');

            // Add the file path to the validated data
            $validatedData['profile_image'] = $filePath;
        }

        $user->update($validatedData);

        return ResponseHelper::success(data: new UserResource($user), message: 'Profile updated successfully');
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
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ( ! Hash::check($request->input('current_password'), $user->password)) {
            return ResponseHelper::error('Invalid current password', 402);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return ResponseHelper::success('Password changed successfully');
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
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
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
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (! $user) {
            return ResponseHelper::error('User not found', 404);
        }

        $user->delete();

        return ResponseHelper::success('User deleted successfully');
    }

}
