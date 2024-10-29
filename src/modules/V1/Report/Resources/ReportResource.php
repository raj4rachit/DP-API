<?php

declare(strict_types=1);

namespace Modules\V1\Report\Resources;

use AllowDynamicProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReportResource",
 *     title="Report Resource",
 *     description="Report resource representation",
 *
 *     @OA\Property(property="uuid", type="string", example="asdfasdfsadfsd"),
 *     @OA\Property(property="name", type="string", example="test name"),
 *     @OA\Property(property="description",type="string", example="Asddfdsfererer"),
 * )
 */
#[AllowDynamicProperties] final class ReportResource extends JsonResource
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
            'description' => $this->description,
        ];
    }
}
