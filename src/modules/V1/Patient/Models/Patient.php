<?php

declare(strict_types=1);

namespace Modules\V1\Patient\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Doctor\Models\Doctor;
use Modules\V1\User\Models\User;

final class Patient extends Model
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // Set the primary key to 'uuid'
    protected $primaryKey = 'uuid';

    // If the UUID is not auto-incrementing, set this to false
    public $incrementing = false;

    // Specify the type of the primary key
    protected $keyType = 'string';

    protected $dateFormat = 'U';

    public string $prefix = 'HOA';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'id_type',
        'id_number',
        'primary_phone',
        'secondary_phone',
        'home_phone',
        'work_phone',
        'languages',
        'arn_number',
        'address_line_1', 'address_line_2', 'city', 'state', 'country', 'postal_code',
    ];

    public $timestamps = false;

    protected $casts = [
        'languages' => 'array', // Convert JSON to array
    ];

    // Define the relationship to User (One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    // Define the relationship to Medical History (One-to-Many)
    public function medicalHistories()
    {
        return $this->hasMany(PatientMedicalHistory::class, 'patient_id', 'uuid');
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'patient_doctor', 'doctor_id', 'patient_id');
    }
}
