<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Country;
use App\Models\State;
use App\Models\Lga;

class InstitutionController extends Controller
{

  public function index()
  {
    return view('institutions.list', [
      'pageTitle' => 'Institutions',
      'institutions' => Institution::all(),
      'countries' => Country::all(),
      'states' => State::all(),
      'lgas' => Lga::all()
    ]);
  }

  public function create()
  {
    $data = $this->request->all();
    $validator = Institution::creationValidator($data);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }

    Institution::unguard();
    $inst = new Institution($data);
    Institution::reguard();
    $inst->save();

    return response()->json([
      'success' => true,
      'message' => 'Institution created',
      'data' => $inst
    ]);
  }

  public function update($id)
  {
    $data = $this->request->all();
    $validator = Institution::updateValidator($data, $id);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }

    $inst = Institution::find($id);
    Institution::unguard();
    $inst->fill($data);
    Institution::reguard();
    $inst->save();

    return response()->json([
        'success' => true,
        'message' => 'Institution updated',
        'data' => $inst
    ]);
  }
}
