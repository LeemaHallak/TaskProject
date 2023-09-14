<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(PageUser::class);
    }

    public function products()
    {
        return $this->hasMany(PageProduct::class);
    }
}
