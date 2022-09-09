<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\UserVerification;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'weight',
        'will',

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


    public function sendVerificationEmail()
    {
        Mail::to($this->email)->send(new UserVerification($this));
    }

    public function rememberToken()
    {
        return JWT::encode(
            [
                'token' => $this->remember_token,
                'iat' => time(),
                'exp' => time() + 60 * 60 * 24 * 7,
            ], env('JWT_SECRET'), 'HS256');
    }

    public function generateRememberToken()
    {
        $this->remember_token = bin2hex(random_bytes(32));
        $this->save();
    }
}
