<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'status',
    ];

    /**
     * A brand can have many medicines.
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}