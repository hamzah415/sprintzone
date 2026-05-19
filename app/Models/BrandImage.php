<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    protected $fillable = [
        'brand_id',
        'image_path'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
