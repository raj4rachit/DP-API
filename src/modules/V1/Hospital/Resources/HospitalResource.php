<?php

declare(strict_types=1);

namespace Modules\V1\Hospital\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\Doctor\Resources\DoctorResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="HospitalResource",
 *     title="Hospital Resource",
 *     description="Representation of a Hospital resource",
 *
 *     @OA\Property(
 *         property="uuid",
 *         type="string",
 *         example="9d445a1c-cee5-4a68-b729-9edf8df71d87",
 *         description="Unique identifier for the hospital (UUID)"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="City General Hospital",
 *         description="Name of the hospital"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         example="1231231234",
 *         description="Hospital's contact phone number"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         example="hospital@example.com",
 *         description="Hospital's contact email address"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="A well-established hospital providing general healthcare services",
 *         description="Brief description of the hospital"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="Active",
 *         description="Current status of the hospital (e.g., Active, Inactive)"
 *     ),
 *     @OA\Property(
 *         property="address_line_1",
 *         type="string",
 *         example="123 Main Street",
 *         description="Primary address line of the hospital"
 *     ),
 *     @OA\Property(
 *         property="address_line_2",
 *         type="string",
 *         example="Suite 200",
 *         description="Secondary address line (optional)"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         example="New York",
 *         description="City where the hospital is located"
 *     ),
 *     @OA\Property(
 *         property="state",
 *         type="string",
 *         example="NY",
 *         description="State where the hospital is located"
 *     ),
 *     @OA\Property(
 *         property="country",
 *         type="string",
 *         example="USA",
 *         description="Country where the hospital is located"
 *     ),
 *     @OA\Property(
 *         property="postal_code",
 *         type="string",
 *         example="10001",
 *         description="Postal code for the hospital"
 *     )
 * )
 */

#[AllowDynamicProperties] final class HospitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'description' => $this->description,
            'doctor' => DoctorResource::collection($this->whenLoaded('doctor')),
        ];
    }
}
