<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Support\Facades\DB;
use App\Utilities\Utility;
use App\Models\User;
use App\Models\Hcp;
use App\Models\HcpUser;
use App\Models\Institution;
use App\Models\InstitutionUser;
use App\Models\MaritalStatus;
use App\Models\Gender;
use App\Models\BloodGroup;
use App\Models\Genotype;


class UserController extends Controller
{

  // agency users
  public function agencyUsers()
  {
    return view('contributors.list', [
      'pageTitle' => 'Agency Users',
      'itemTitle' => 'Agency User',
      'maritalStatuses' => MaritalStatus::all(),
      'genders' => Gender::all(),
      'genotypes' => Genotype::all(),
      'bloodGroups' => BloodGroup::all(),
      'users' => User::whereIn('id', DB::table('agency_users')->pluck('user_id'))->with(['blood_group', 'genotype', 'marital_status', 'gender'])->get()->all(),
    ]);
  }

  // individual contributors
  public function contributors()
  {
    return view('contributors.list', [
      'pageTitle' => 'Individual Contributors',
      'itemTitle' => 'Individual Contributor',
      'maritalStatuses' => MaritalStatus::all(),
      'genders' => Gender::all(),
      'genotypes' => Genotype::all(),
      'bloodGroups' => BloodGroup::all(),
      /*'users' => User::whereNotIn('id', DB::table('agency_users')->pluck('user_id'))
        ->whereNotIn('id', DB::table('institution_user')->pluck('user_id'))
        ->whereNotIn('id', DB::table('hcp_user')->pluck('user_id'))
        ->with(['blood_group', 'genotype', 'marital_status', 'gender'])
        ->get()->all(),*/
      'users' => User::whereIn('id', DB::table('individuals')->pluck('user_id'))->with(['blood_group', 'genotype', 'marital_status', 'gender'])->get()->all(),
    ]);
  }

  // hcp users
  public function hcpUsers($id)
  {
    $hcp = Hcp::find($id);
    return view('hcps.list-users', [
      'pageTitle' => 'HCP users',
      'hcp' => $hcp,
      'maritalStatuses' => MaritalStatus::all(),
      'genders' => Gender::all(),
      'genotypes' => Genotype::all(),
      'bloodGroups' => BloodGroup::all(),
      'users' => $hcp->users()->with(['user', 'user.genotype', 'user.gender', 'user.blood_group', 'user.marital_status'])->get()->all()
    ]);
  }

  // institution users
  public function institutionUsers($id)
  {
    $institution = Institution::find($id);
    return view('institutions.list-users', [
      'pageTitle' => 'Institution users',
      'institution' => $institution,
      'maritalStatuses' => MaritalStatus::all(),
      'genders' => Gender::all(),
      'genotypes' => Genotype::all(),
      'bloodGroups' => BloodGroup::all(),
      'users' => $institution->users()->with(['user', 'user.genotype', 'user.gender', 'user.blood_group', 'user.marital_status'])->get()->all()
    ]);
  }

  public function createUser($params)
  {
    $data = $this->request->all();
    $validator = User::creationValidator($data);
    if ($validator->fails()) {
      return array(
        'error' => 1,
        'response' => response()->json([
          'success' => false,
          'message' => 'Validation failed',
          'data' => $validator->errors()->messages()
        ], 422)
      );
    }
    if(!isset($params['type']) || empty($params['type'])){
      return false;
    }
    if($params['type'] == 'agency-user'){
      $user = User::createAgencyUser($data);
    }
    if($params['type'] == 'individual-contributor'){
      $user = User::createIndividualContributor($data);
    }
    if($params['type'] == 'hcp-user'){
      $user = User::createHcpUser($params['hcp'], $data);
      $user->loadMissing(['user', 'user.genotype', 'user.gender', 'user.blood_group', 'user.marital_status']);
    }
    if($params['type'] == 'institution-user'){
      $user = User::createInstitutionUser($params['institution'], $data);
      $user->loadMissing(['user', 'user.genotype', 'user.gender', 'user.blood_group', 'user.marital_status']);
    }
    //Log::info('User creation error:: '.$user);
    return $user;
  }

  public function updateUser($id)
  {
    $data = $this->request->all();
    $validator = User::updateValidator($data, $id);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }
    if(isset($data['password']) )
    {
      $data['temp_password'] = Utility::paul($data['password']);
    }
    User::find($id)->fill($data)->save();
    return User::where('id', $id)->with(['blood_group', 'genotype', 'marital_status', 'gender'])->first();
  }

  public function deleteUser($id)
  {
    return (bool) User::destroy($id);
  }

  public function createAgencyUser()
  {
    $tryCreate = $this->createUser(['type' => 'agency-user']);
    if(array_key_exists('error', $tryCreate)){
      return $tryCreate['response'];
    }
    return response()->json([
      'success' => true,
      'message' => 'Agency User created',
      'data' => $tryCreate
    ]);
  }

  public function createIndividualContributor()
  {
    $tryCreate = $this->createUser(['type' => 'individual-contributor']);
    if(array_key_exists('error', $tryCreate)){
      return $tryCreate['response'];
    }
    return response()->json([
      'success' => true,
      'message' => 'Individual Contributor created',
      'data' => $tryCreate
    ]);
  }

  public function createHcpUser()
  {
    $data = $this->request->all();
    $hcp = Hcp::find($data['hcp_id']);
    if (empty($hcp)) {
      return response()->json([
        'success' => false,
        'message' => 'HCP not found',
        'data' => []
      ], 400);
    }
    $tryCreate = $this->createUser(['type' => 'hcp-user', 'hcp' => $hcp]);
    if(array_key_exists('error', $tryCreate)){
      return $tryCreate['response'];
    }
    return response()->json([
      'success' => true,
      'message' => 'Hcp user created',
      'data' => $tryCreate
    ]);
  }

  public function createInstitutionUser()
  {
    $data = $this->request->all();
    $institution = Institution::find($data['institution_id']);
    if (empty($institution)) {
      return response()->json([
        'success' => false,
        'message' => 'Institution not found',
        'data' => []
      ], 400);
    }
    $tryCreate = $this->createUser(['type' => 'institution-user', 'institution' => $institution]);
    if(array_key_exists('error', $tryCreate)){
      return $tryCreate['response'];
    }
    return response()->json([
      'success' => true,
      'message' => 'Institution user created',
      'data' => $tryCreate
    ]);
  }

  public function updateAgencyUser($id)
  {
    return response()->json([
      'success' => true,
      'message' => 'Agency User updated',
      'data' => $this->updateUser($id)
    ]);
  }

  public function updateIndividualContributor($id)
  {
    return response()->json([
      'success' => true,
      'message' => 'Individual Contributor updated',
      'data' => $this->updateUser($id)
    ]);
  }

  public function updateHcpUser()
  {
    $data = $this->request->all();
    if (HcpUser::where('user_id', $data['id'])->where('hcp_id', $data['hcp_id'])->get()->count() <= 0) {
      return response()->json([
        'success' => false,
        'message' => 'HCP user not found',
        'data' => []
      ], 400);
    }
    return response()->json([
      'success' => true,
      'message' => 'Hcp user updated',
      'data' => $this->updateUser($data['id'])
    ]);
  }

  public function updateInstitutionUser()
  {
    $data = $this->request->all();
    if (InstitutionUser::where('user_id', $data['id'])->where('institution_id', $data['institution_id'])->get()->count() <= 0) {
      return response()->json([
        'success' => false,
        'message' => 'Institution user not found',
        'data' => []
      ], 400);
    }
    return response()->json([
      'success' => true,
      'message' => 'Institution user updated',
      'data' => $this->updateUser($data['id'])
    ]);
  }

  public function deleteAgencyUser($id)
  {
    $success = $this->deleteUser($id);
    return response()->json([
      'success' => $success,
      'message' => $success ? 'User deleted' : 'Could not delete User'
    ]);
  }

  public function deleteIndividualContributor($id)
  {
    $success = $this->deleteUser($id);
    return response()->json([
      'success' => $success,
      'message' => $success ? 'User deleted' : 'Could not delete User'
    ]);
  }

  public function deleteHcpUser($id)
  {
    $success = $this->deleteUser($id);
    return response()->json([
      'success' => $success,
      'message' => $success ? 'User deleted' : 'Could not delete User'
    ]);
  }

  public function deleteInstitutionUser($id)
  {
    $success = $this->deleteUser($id);
    return response()->json([
      'success' => $success,
      'message' => $success ? 'User deleted' : 'Could not delete User'
    ]);
  }

  public function getTP($id)
  {
    return Utility::getTP(User::where('id', $id)->pluck('temp_password'));
  }

  public function generateUserCode()
  {
    $seed = "USER-";
    a:
    $code = Utility::generateRandomNumber(8);
    $code = $seed.$code;
    if(User::where('verification_no', $code)->count() <= 0){
      return $code;
    }else{
      goto a;
    }
  }

  public function getUserCode()
  {
    return response()->json([
      'success' => true,
      'message' => 'User verification_no generated',
      'data' => array('verification_no' => $this->generateUserCode())
    ]);
  }





}