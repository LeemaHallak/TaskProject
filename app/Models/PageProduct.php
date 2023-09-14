<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageProduct extends Model
{
    use HasFactory;

    
    protected $guarded = [
        'id'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
}
