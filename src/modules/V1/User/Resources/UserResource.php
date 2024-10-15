<?php

declare(strict_types=1);

namespace Modules\V1\User\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\StringHelper;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User resource representation",
 *
 *     @OA\Property(property="uuid", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="gender", type="string", example="male"),
 *     @OA\Property(property="dob", type="string", example="2022-02-25"),
 *     @OA\Property(property="mobile_no", type="string", example="1234567895"),
 *     @OA\Property(property="address", type="string", example="Test Address"),
 *     @OA\Property(property="profile_image", type="string", example=""),
 * )
 */
#[AllowDynamicProperties] final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //dd($request);
        return [
            'uuid' => $this->uuid,
            'name' => StringHelper::toTitleCase($this->first_name.' '.$this->last_name),
            'first_name' => StringHelper::toTitleCase($this->first_name),
            'last_name' => StringHelper::toTitleCase($this->last_name),
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'mobile_no' => $this->mobile_no,
            'user_type' => $this->user_type,
            'roles' => $this->roles->pluck('name'), // Adjust based on your relationship and desired fields
            'permissions' => $this->getAllPermissions(), // Adjust accordingly
        ];
    }

    private function getAllPermissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique(); // Use unique to avoid duplicates
    }
}
