<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function pageProduct()
    {
        return $this->belongsTo(PageProduct::class);
    }
}
