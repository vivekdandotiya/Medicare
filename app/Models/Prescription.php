<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'patient_name',
        'doctor_name',
        'file_path',
        'status',
        'notes',
    ];

    /**
     * Get the user that owns the prescription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
