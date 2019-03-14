<?php

namespace App\Http\Controllers;

use Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hcp;
use App\Models\HcpUser;
use App\Models\Country;
use App\Models\HcpType;
use App\Models\State;
use App\Models\Lga;
use App\Models\MaritalStatus;
use App\Models\Gender;
use App\Models\BloodGroup;
use App\Models\Genotype;
use Illuminate\Support\Str;

class HcpController extends Controller
{

  public function index()
  {
    return view('hcps.list', [
      'pageTitle' => 'HCPs',
      'hcps' => Hcp::all(),
      'countries' => Country::all(),
      'hcp_types' => HcpType::all(),
      'states' => State::all(),
      'lgas' => Lga::all()
    ]);
  }

  public function create()
  {
    $data = $this->request->all();
    $validator = Hcp::creationValidator($data);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }

    Hcp::unguard();
    $hcp = new Hcp($data);
    Hcp::reguard();
    $hcp->save();

    return response()->json([
      'success' => true,
      'message' => 'Hcp created',
      'data' => $hcp
    ]);
  }

  public function update($id)
  {
    $data = $this->request->all();
    $validator = Hcp::updateValidator($data, $id);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }

    $hcp = Hcp::find($id);
    Hcp::unguard();
    $hcp->fill($data);
    Hcp::reguard();
    $hcp->save();

    return response()->json([
        'success' => true,
        'message' => 'Hcp updated',
        'data' => $hcp
    ]);
  }

  public function indexUsers($id)
  {
    $hcp = Hcp::find($id);
    // Hcp::where('id', $hcpId); Same thing

    return view('hcps.list-users', [
      'pageTitle' => 'HCP users',
      'hcp' => $hcp,
      'maritalStatuses' => MaritalStatus::all(),
      'genders' => Gender::all(),
      'genotypes' => Genotype::all(),
      'bloodGroups' => BloodGroup::all(),
      'users' => $hcp->users()->with('user')->get()
    ]);
  }

  public function createUser($id)
  {
    $hcp = Hcp::find($id);

    if (empty($hcp))
    {
      return response()->json([
        'success' => false,
        'message' => 'HCP not found',
      ], 400);
    }

    $data = $this->request->all();
    $validator = User::creationValidator($data);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }

    // Create password if none is provided
    if(!isset($data['password']) )
    {
      $defaultPassword = Str::random(10);
      $data['password'] = $defaultPassword;
      $userStr = "(email: {$data['email']}, phone: {$data['phone']})";
      Log::warning("Assigned '$defaultPassword' as default password to $userStr"); // Log so we can easily retrieve
    }

    $hcpUser = User::createHcpUser($hcp, $data);

    $hcpUser->loadMissing('user'); // The front-end expects the user to be loaded

    return response()->json([
      'success' => true,
      'message' => 'Hcp user created',
      'data' => $hcpUser
    ]);
  }

  public function updateUser($id, $userId)
  {
    $data = $this->request->all();
    $hcpUser = HcpUser::find($userId);

    $validator = User::updateValidator($data, $hcpUser->user_id);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }

    $user = User::find($hcpUser->user_id);
    User::unguard();
    $user->fill($data);
    User::reguard();
    $user->save();

    $hcpUser->loadMissing('user'); // The front-end expects the user to be loaded

    return response()->json([
        'success' => true,
        'message' => 'Hcp user updated',
        'data' => $hcpUser
    ]);
  }
}
