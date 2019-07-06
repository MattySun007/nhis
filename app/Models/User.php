<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Illuminate\Validation\Rule;
use App\Traits\HasPermission;
use App\Utilities\Utility;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
  use Notifiable, HasPermission;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'verification_no', 'password', 'temp_password', 'contribution_amount', 'blood_group_id', 'gender_id', 'marital_status_id', 'genotype_id', 'colour', 'height', 'date_of_birth','first_name','middle_name', 'last_name','email', 'phone'
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

  /**
   * This is a mutator, mutating every attribute key password
   * @param $pass
   */
  public function setPasswordAttribute($pass){

    $this->attributes['password'] = Hash::make($pass);

  }

  public function blood_group()
  {
    return $this->belongsTo(BloodGroup::class);
  }

  public function gender()
  {
    return $this->belongsTo(Gender::class);
  }

  public function genotype()
  {
    return $this->belongsTo(Genotype::class);
  }

  public function marital_status()
  {
    return $this->belongsTo(MaritalStatus::class);
  }

  public function userBiometric()
  {
    return $this->hasOne(UserBiometric::class);
  }

  public function agencyUser()
  {
    return $this->hasOne(AgencyUser::class);
  }

  public function institutionUser()
  {
    return $this->hasMany(InstitutionUser::class);
  }

  public function individualUser()
  {
    return $this->hasMany(Individual::class);
  }

  public function adoptee()
  {
    return $this->hasOne(Adoptee::class);
  }

  /**
   * Hcp staff, a staff/user belongs to one Hcp
   */
  public function hcpUser()
  {
    return $this->hasMany(HcpUser::class);
  }

  /**
   * contributor Hcps, a contributor can have more than one Hcp
   */
  public function hcpIndividual()
  {
    return $this->hasMany(HcpIndividual::class);
  }

  public function isAgencyUser()
  {
    return (bool) $this->agencyUser()->count();
  }

  public function isAdoptee()
  {
    return (bool) $this->adoptee()->count();
  }

  public function isHcpUser()
  {
    return (bool) $this->hcpUser()->count();
  }

  public function isInstitutionUser()
  {
    return (bool) $this->institutionUser()->count();
  }

  public function isIndividualUser()
  {
    return (bool) $this->individualUser()->count();
  }

  public function getFullNameAttribute()
  {
    return $this->middle_name ? "$this->first_name $this->middle_name $this->last_name" : "$this->first_name $this->last_name";
  }

  public function getUserTypeAttribute()
  {
    if ($this->isAgencyUser())
    {
      return "Agency User";
    }

    if ($this->isInstitutionUser())
    {
      return "Institution User";
    }

    if ($this->isHcpUser())
    {
      return "Hcp User";
    }

    if ($this->isAdoptee())
    {
      return "Adoptee";
    }

    if ($this->isIndividualUser())
    {
      return "Individual Contributor";
    }

    return "Unknown";
  }

  public function getUserInstitutionsAttribute()
  {
    return InstitutionUser::where('user_id', auth()->user()->id)->pluck('institution_id');
  }

  public function getUserHcpsAttribute()
  {
    return HcpUser::where('user_id', auth()->user()->id)->pluck('hcp_id');
  }

  public function getContributorHcpsAttribute()
  {
    if(auth()->user()->user_type == 'Individual Contributor'){
      return HcpIndividual::where('user_id', auth()->user()->id)->pluck('hcp_id');
    }
    if(auth()->user()->user_type == 'Institution User'){
      return DB::table('hcp_institution')
        ->join('institution_user', 'institution_user.institution_id', '=', 'hcp_institution.institution_id')
        ->select('hcp_institution.hcp_id')
        ->get();
    }
    return false;
  }

  public function adoptees()
  {
    return $this->hasMany(Adoptee::class);
  }

  public function assignAgencyUserPermissions()
  {
    /*$this->givePermissions(
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
      'institution-users:read',
      'institution-users:delete',
      'individual-contributors:read',
      'individual-contributors:update',
      'individual-contributors:create',
      'individual-contributors:delete',
      'institution-hcp:read',
      'institution-hcp:create',
      'institution-hcp:delete',
      'claims:read',
      'claims:manage',
      'contributions:manage',
      'agency-users:create',
      'agency-users:read',
      'agency-users:update',
      'agency-users:delete'
    );*/
    // assign default permissions for everybody, others should be assignable by a user who has permissions:manage permission
    $this->givePermissions(
      'contributions:update',
      'contributions:read',
      'contributions:create',
      'contributions:delete',
      'adoptions:create',
      'adoptions:read',
      'adoptions:create',
      'claims:read',
      'adoption:create',
      'adoption:read',
      'adoption:update',
      'adoption:delete'
    );
  }

  public function assignHcpUserPermissions()
  {
    /*$this->givePermissions(
      'hcps:update',
      'hcps:read',
      'hcp-users:read',
      'hcp-users:update',
      'hcp-users:create',
      'hcp-users:delete',
      'hcp-users:manage-permissions',
      'treatments:read',
      'treatments:update',
      'treatments:create',
      'treatments:delete',
      'claims:read',
      'adoption:create',
      'adoption:read',
      'adoption:update',
      'adoption:delete'
    );*/
    // assign default permissions for everybody, others should be assignable by a user who has permissions:manage permission
    $this->givePermissions(
      'contributions:update',
      'contributions:read',
      'contributions:create',
      'contributions:delete',
      'adoptions:create',
      'adoptions:read',
      'adoptions:create',
      'claims:read',
      'adoption:create',
      'adoption:read',
      'adoption:update',
      'adoption:delete'
    );
  }

  public function assignInstitutionUserPermissions()
  {
    /*$this->givePermissions(
      'institutions:update',
      'institutions:read',
      'institution-users:read',
      'institution-users:update',
      'institution-users:create',
      'institution-users:delete',
      'institution-users:manage-permissions',
      'claims:read',
      'adoption:create',
      'adoption:read',
      'adoption:update',
      'adoption:delete'
    );*/
    // assign default permissions for everybody, others should be assignable by a user who has permissions:manage permission
    $this->givePermissions(
      'contributions:update',
      'contributions:read',
      'contributions:create',
      'contributions:delete',
      'adoptions:create',
      'adoptions:read',
      'adoptions:create',
      'claims:read',
      'adoption:create',
      'adoption:read',
      'adoption:update',
      'adoption:delete'
    );
    $this->assignIndividualContributorPermissions();
  }

  public function assignIndividualContributorPermissions()
  {
    $this->givePermissions(
      'contributions:update',
      'contributions:read',
      'contributions:create',
      'contributions:delete',
      'adoptions:create',
      'adoptions:read',
      'adoptions:create',
      'claims:read',
      'adoption:create',
      'adoption:read',
      'adoption:update',
      'adoption:delete'
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

  public static function createHcpUser(Hcp $hcp, array $data)
  {
    $p = Utility::generatePassword($data);
    if($p){
      $data['password'] = $p['password'];
      $data['temp_password'] = $p['temp_password'];
    }

    $user = User::create($data);
    $user->assignHcpUserPermissions();
    return HcpUser::create(['user_id' => $user->id, 'hcp_id' => $hcp->id]);
  }

  public static function createInstitutionUser(Institution $institution, array $data)
  {
    $p = Utility::generatePassword($data);
    if($p){
      $data['password'] = $p['password'];
      $data['temp_password'] = $p['temp_password'];
    }

    $user = User::create($data);
    $user->assignInstitutionUserPermissions();
    return InstitutionUser::create(['user_id' => $user->id, 'institution_id' => $institution->id]);
  }

  public static function createIndividualContributor(array $data)
  {
    $p = Utility::generatePassword($data);
    if($p){
      $data['password'] = $p['password'];
      $data['temp_password'] = $p['temp_password'];
    }

    $user = User::create($data);
    $user->assignIndividualContributorPermissions();
    Individual::create(['user_id' => $user->id]);
    return User::where('id', $user->id)->with(['blood_group', 'genotype', 'marital_status', 'gender'])->first();
    // $institutionUser->loadMissing(['user', 'user.genotype', 'user.gender', 'user.blood_group', 'user.marital_status']);
  }

  public static function createAgencyUser(array $data)
  {
    $p = Utility::generatePassword($data);
    if($p){
      $data['password'] = $p['password'];
      $data['temp_password'] = $p['temp_password'];
    }

    $user = User::create($data);
    $user->assignAgencyUserPermissions();
    AgencyUser::create(['user_id' => $user->id]);
    return User::where('id', $user->id)->with(['blood_group', 'genotype', 'marital_status', 'gender'])->first();
  }


}
