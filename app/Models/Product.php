<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function category()
    {
        return $this->belongsTo(Product::class);
    }

    public function pages()
    {
        return $this->hasMany(PageProduct::class);
    }
}
