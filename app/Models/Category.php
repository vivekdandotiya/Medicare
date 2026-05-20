<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];

    /**
     * A category can have many medicines.
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}