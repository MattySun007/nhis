<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasPermission;

class User extends Authenticatable
{
    use Notifiable, HasPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function agencyUser()
    {
        return $this->hasOne(AgencyUser::class);
    }

    public function adoptees()
    {
        return $this->hasMany(Adoptee::class);
    }

    public function getFullNameAttribute()
    {
        return $this->middle_name ?
            "$this->first_name $this->middle_name $this->last_name" :
            "$this->first_name $this->last_name";
    }

    public function assignAgencyUserPermissions()
    {
        $this->givePermissions(
            'institutions:read',
            'institutions:create',
            'institutions:update',
            'institutions:delete',
            'hcps:create',
            'hcps:read',
            'hcp-users:create'
        );
    }

    public function assignHcpUserPermissions()
    {
        $this->givePermissions(
            'hcps:create',
            'hcps:update',
            'hcps:read',
            'hcp-users:create'
        );
    }
}
