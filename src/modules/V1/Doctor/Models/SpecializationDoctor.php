<?php

declare(strict_types=1);

namespace Modules\V1\Doctor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

final class SpecializationDoctor extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'specialization_doctor';

    protected $primaryKey = null; // No single primary key column

    protected $fillable = [
        'doctor_id',
        'specialization_id',
    ];

    public $timestamps = false;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'uuid');
    }
}
