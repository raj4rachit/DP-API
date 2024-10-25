<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

final class DoctorSpecialization extends Model
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'uuid';
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public $timestamps = false;

    // Many-to-Many Relationship with Doctor via specialization_doctors table
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'specialization_doctor', 'specialization_id', 'doctor_id');
    }
}
