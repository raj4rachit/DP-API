<?php

declare(strict_types=1);

namespace Modules\V1\Lab\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Lab\Models\Lab;

final class LabReport extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'lab_id',
        'report_id',
    ];

    public $timestamps = false;

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'uuid');
    }
}
