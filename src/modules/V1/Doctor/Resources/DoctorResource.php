<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\Hospital\Resources\HospitalResource;
use Modules\V1\Patient\Resources\PatientResource;
use Modules\V1\Subscription\Resources\SubscriptionResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DoctorResource",
 *     title="Doctor Resource",
 *     description="Representation of a Doctor resource, including personal details and associated relationships.",
 *
 *     @OA\Property(
 *         property="uuid",
 *         type="string",
 *         example="123e4567-e89b-12d3-a456-426614174000",
 *         description="Unique identifier for the doctor (UUID)"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         example="John",
 *         description="Doctor's first name"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         example="Doe",
 *         description="Doctor's last name"
 *     ),
 *     @OA\Property(
 *         property="gender",
 *         type="string",
 *         example="Male",
 *         description="Doctor's gender"
 *     ),
 *     @OA\Property(
 *         property="dob",
 *         type="string",
 *         format="date",
 *         example="1980-01-01",
 *         description="Doctor's date of birth (YYYY-MM-DD)"
 *     ),
 *     @OA\Property(
 *         property="address_line_1",
 *         type="string",
 *         example="123 Health St",
 *         description="First line of the doctor's address"
 *     ),
 *     @OA\Property(
 *         property="address_line_2",
 *         type="string",
 *         example="Suite 101",
 *         description="Second line of the doctor's address"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         example="New York",
 *         description="City where the doctor is located"
 *     ),
 *     @OA\Property(
 *         property="state",
 *         type="string",
 *         example="NY",
 *         description="State where the doctor is located"
 *     ),
 *     @OA\Property(
 *         property="country",
 *         type="string",
 *         example="USA",
 *         description="Country where the doctor is located"
 *     ),
 *     @OA\Property(
 *         property="postal_code",
 *         type="string",
 *         example="10001",
 *         description="Postal code of the doctor's address"
 *     ),
 *     @OA\Property(
 *         property="contact_phone",
 *         type="string",
 *         example="555-1234",
 *         description="Contact phone number for the doctor"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/UserResource",
 *         description="User profile associated with the doctor"
 *     ),
 *     @OA\Property(
 *         property="hospital",
 *         ref="#/components/schemas/HospitalResource",
 *         description="Hospital where the doctor works"
 *     ),
 *     @OA\Property(
 *         property="specializations",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/DoctorSpecializationResource"),
 *         description="List of specializations the doctor is trained in"
 *     ),
 *
 *     @OA\Property(
 *         property="patients",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/PatientResource"),
 *         description="List of patients assigned to the doctor"
 *     ),
 *
 *     @OA\Property(
 *         property="subscriptions",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/SubscriptionResource"),
 *         description="List of subscriptions associated with the doctor"
 *     )
 * )
 */
#[AllowDynamicProperties] final class DoctorResource extends JsonResource
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
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'contact_phone' => $this->contact_phone,
            'user' => new UserResource($this->whenLoaded('user')),
            'hospital' => new HospitalResource($this->whenLoaded('hospital')),
            'specializations' => DoctorSpecializationResource::collection($this->whenLoaded('specializations')),
            'patients' => PatientResource::collection($this->whenLoaded('patients')),
            'subscriptions' => SubscriptionResource::collection($this->whenLoaded('subscription')),
        ];
    }
}
