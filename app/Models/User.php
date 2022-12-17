<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'referrer_phone',
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
    ];

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function softEtherAccounts()
    {
        return $this->hasMany(SoftEtherAccount::class, 'assigned_to');
    }

    /**
     * Override the field which is used for username in the authentication
     * @param string $username
     * @return User
     */
    public function findForPassport(string $username)
    {
        return $this->where('phone', $username)->first();
    }

    /**
     * @param string $username
     * @return mixed
     */
    public static function findByUserName(string $username)
    {
        return static::where('phone', $username)->first();
    }
}
