<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PageUser;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function pagesUsers()
    {
        return $this->hasMany(PageUser::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function pages()
    {
        return $this->belongsTo(Page::class);
    }
    
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }
    
}
