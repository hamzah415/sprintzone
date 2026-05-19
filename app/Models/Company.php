<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'registration_number', 'tax_id', 
        'email', 'phone', 'website', 'logo', 
        'address', 'city', 'province', 'postal_code', 
        'country', 'description', 'industry', 'is_active'
    ];

    /**
     * Casting tipe data agar lebih konsisten.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
