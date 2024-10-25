<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\V1\Hospital\Models\Hospital;
use Modules\V1\User\Models\User;

final class Doctor extends Model
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
        'user_id',
        'hospital_id',
        'contact_phone',
        'clinic_address',
    ];

    public $timestamps = false;

    protected $casts = [
        'specialization' => 'array', // Convert JSON to array
    ];

    // Define the relationship to User (One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    // Define the relationship to Hospital (One-to-One)
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'uuid');
    }

    // Many-to-Many Relationship with Specializations via specialization_doctors table
    public function specializations()
    {
        return $this->belongsToMany(DoctorSpecialization::class, 'specialization_doctor', 'doctor_id', 'specialization_id');
    }
}
