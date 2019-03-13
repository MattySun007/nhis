<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Illuminate\Validation\Rule;
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
            'hcps:update',
            'hcps:read',
            'hcp-users:read',
            'hcp-users:update',
            'hcp-users:create',
            'hcp-users:delete',
            'hcp-users:manage-permissions',
            'institution-users:manage-permissions',
            'institution-users:create',
            'institution-users:update',
            'institution-users:read'
        );
    }

    public function assignHcpUserPermissions()
    {
        $this->givePermissions(
            'hcps:update',
            'hcps:read',
            'hcp-users:read',
            'hcp-users:update',
            'hcp-users:create',
            'hcp-users:delete',
            'hcp-users:manage-permissions'
        );
    }

    public static function creationValidator(array $data)
    {
        $rules = [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender_id' => 'required|exists:genders,id',
            'marital_status_id' => 'required|exists:marital_statuses,id',
            'email' => 'required|email|max:125|unique:users',
            'phone' => 'required|regex:/^\d{7,11}$/|max:15|unique:users'
        ];

        if (!empty($data['middle_name']))
        {
            $rules['middle_name'] = 'string|max:50';
        }

        if (!empty($data['genotype_id']))
        {
            $rules['genotype_id'] = 'exists:genotypes,id';
        }

        if (!empty($data['blood_group_id']))
        {
            $rules['blood_group_id'] = 'exists:blood_groups,id';
        }

        return Validator::make($data, $rules);
    }

    public static function updateValidator(array $data, $id)
    {
        $rules = [];

        if (!empty($data['first_name']))
        {
            $rules['first_name'] = 'string|max:50';
        }

        if (!empty($data['middle_name']))
        {
            $rules['middle_name'] = 'string|max:50';
        }

        if (!empty($data['last_name']))
        {
            $rules['last_name'] = 'string|max:50';
        }

        if (!empty($data['genotype_id']))
        {
            $rules['genotype_id'] = 'exists:genotypes,id';
        }

        if (!empty($data['blood_group_id']))
        {
            $rules['blood_group_id'] = 'exists:blood_groups,id';
        }

        if (!empty($data['gender_id']))
        {
            $rules['gender_id'] = 'exists:genders,id';
        }

        if (!empty($data['marital_status_id']))
        {
            $rules['marital_status_id'] = 'exists:marital_statuses,id';
        }

        if (!empty($data['email']))
        {
            $rules['email'] = ['email', 'max:125', Rule::unique('users')->ignore($id)];
        }

        if (!empty($data['phone']))
        {
            $rules['phone'] = ['regex:/^\d{7,11}$/', 'max:15', Rule::unique('users')->ignore($id)];
        }

        return Validator::make($data, $rules);
    }
}
