<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Adoptee;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{

  public function index($id = null)
  {
    $id = is_null($id) ? auth()->user()->id : $id;
    $adoptor = User::find($id);
    return view('contributors.list-adoption', [
      'pageTitle' => 'Adopt Contributor',
      'user_id' => $id,
      'adoptees' => $adoptor->adoptees()->with(['adopteeDetails.genotype', 'adopteeDetails.gender', 'adopteeDetails.blood_group', 'adopteeDetails.marital_status'])->get()->all()
    ]);
  }

  public function create()
  {
    $data = $this->request->all();
    if (!isset($data['adoptee_id']) || !isset($data['user_id'])) {
      return response()->json([
        'success' => false,
        'message' => 'Adoptor or Adoptee not provided',
        'data' => []
      ], 400);
    }
    Adoptee::create(['user_id' => $data['user_id'], 'adoptee_id' => $data['adoptee_id']]);
    $adoptor = User::find($data['user_id']);
    return response()->json([
      'success' => true,
      'message' => 'Adoptee created',
      'data' => $adoptor->adoptees()->with(['adopteeDetails.genotype', 'adopteeDetails.gender', 'adopteeDetails.blood_group', 'adopteeDetails.marital_status'])->get()->all()
    ]);
  }

  public function deleteAdoption($id)
  {
    $adop = Adoptee::find($id);
    $adoptor = User::find($adop['user_id']);
    if (empty($adoptor))
    {
      return response()->json([
        'success' => false,
        'message' => 'Adoptor not found',
      ], 400);
    }
    Adoptee::destroy(array($id));
    return response()->json([
      'success' => true,
      'message' => 'Adoptee deleted',
      'data' => $adoptor->adoptees()->with(['adopteeDetails.genotype', 'adopteeDetails.gender', 'adopteeDetails.blood_group', 'adopteeDetails.marital_status'])->get()->all()
    ]);
  }







}
