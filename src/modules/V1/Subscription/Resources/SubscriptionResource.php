<?php

declare(strict_types=1);

namespace Modules\V1\Subscription\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\V1\Package\Resources\PackageResource;
use Modules\V1\User\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SubscriptionResource",
 *     title="Subscription Resource",
 *     description="Subscription resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example="asdfasdfsadfsd"),
 *     @OA\Property(property="user_id", type="string", example="user's id"),
 *     @OA\Property(property="package_id", type="string", example="package's id"),
 *     @OA\Property(property="name", type="string", example="test name"),
 *     @OA\Property(property="payment_transaction_id",type="string", example="Asddfdsfererer"),
 *     @OA\Property(property="patient_count",type="string", example="10"),
 *     @OA\Property(property="patient_charge",type="string", example="100"),
 *     @OA\Property(property="amount",type="double", example="1000"),
 *     @OA\Property(property="start_date",type="date", example="2024-01-01"),
 *     @OA\Property(property="end_date",type="date", example="2024-02-01"),
 * )
 */
#[AllowDynamicProperties] final class SubscriptionResource extends JsonResource
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
            'patient_count' => $this->patient_count,
            'patient_charge' => $this->patient_charge,
            'amount' => $this->amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user' => new UserResource($this->whenLoaded('user')),
            'package' => new PackageResource($this->whenLoaded('package')),
        ];
    }
}
