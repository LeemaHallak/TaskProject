<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function reciever()
    {
        return $this->belongsTo(User::class);
    }
}
