<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        'is_active',
        'contact_person',
        'contact_number',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'company_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isAdmin()
    {
        $adminRoles = [
            'super-administrator',
            'administrative-service-officer',
            'registry-coordinator',
            'cultural-registry-data-officer',
            'precup-head',
            'geographic-information-systems-analyst',
        ];

        return $this->roles
            ->pluck('slug')
            ->intersect($adminRoles)
            ->isNotEmpty();
    }

    public function sendEmailVerificationNotificationFunction($password)
    {
        $this->notify(new \App\Notifications\VerifyEmail($password));
    }

    // public function routeNotificationForMail()
    // {
    //     $try = env('APP_ENV');
    //     $email = env('MAIL_TEST_ACCOUNT');
    
    //     // Check if the environment is not 'production'
    //     if ($try !== 'production') {
    //         return $email;
    //     }
    
    //     // If 'production', use the default email address
    //     return $this->email;
    // }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole($roleSlug)
    {
        return $this->roles->contains('slug', $roleSlug);
    }
}