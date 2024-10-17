<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

final class PatientMedicalHistory extends Model
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'patient_id',
        'medical_aid',
        'race',
        'ethnicity',
        'mrn_number',
    ];

    public $timestamps = false;

    // Define the relationship to Patient (Inverse One-to-Many)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'uuid');
    }
}
