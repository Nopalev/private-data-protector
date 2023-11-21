<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use phpseclib3\Crypt\RC4;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'username',
        'email',
        'password'
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

    public function biodata(): HasOne
    {
        return $this->hasOne(Biodata::class);
    }

    public function userKey(): HasOne
    {
        return $this->hasOne(UserKey::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function requestKeyOwner(): HasMany
    {
        return $this->hasMany(RequestKey::class, 'user_id_owner');
    }

    public function requestKeyReq(): HasMany
    {
        return $this->hasMany(RequestKey::class, 'user_id_req');
    }
}
