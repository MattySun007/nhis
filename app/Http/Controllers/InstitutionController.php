<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Validation\Rule;
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
    $validator = Validator::make($data, [
      'code' => 'required|string|max:20|unique:institutions',
      'name' => 'required|string|max:45|unique:institutions',
      'address' => 'required|string|max:125',
      'rcc_number' => 'string|max:100',
      'country_id' => 'required|exists:countries,id',
      'state_id' => 'required|exists:states,id',
      'lga_id' => 'required|exists:lgas,id',
      'town_id' => 'exists:towns,id',
      'email' => 'required|email|max:125|unique:institutions',
      'phone' => 'regex:/^\d{7,11}$/|max:15|unique:institutions'
    ]);
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
    $validator = Validator::make($data, [
      'address' => 'string|max:125',
      'rcc_number' => 'string|max:100',
      'country_id' => 'exists:countries,id',
      'state_id' => 'exists:states,id',
      'lga_id' => 'exists:lgas,id',
      'town_id' => 'exists:towns,id',
      'code' => [
        'string',
        'max:20',
        Rule::unique('institutions')->ignore($id)
      ],
      'name' => [
        'string',
        'max:45',
        Rule::unique('institutions')->ignore($id)
      ],
      'email' => [
        'email',
        'max:125',
        Rule::unique('institutions')->ignore($id)
      ],
      'phone' => [
        'regex:/^\d{7,11}$/',
        'max:15',
        Rule::unique('institutions')->ignore($id)
      ]
    ]);
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
