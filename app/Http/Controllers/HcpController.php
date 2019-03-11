<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Hcp;
use App\Models\Country;
use App\Models\State;
use App\Models\Lga;

class HcpController extends Controller
{

  public function index()
  {
    return view('hcps.list', [
      'pageTitle' => 'HCPs',
      'hcps' => Hcp::all(),
      'countries' => Country::all(),
      'states' => State::all(),
      'lgas' => Lga::all()
    ]);
  }

  public function create()
  {
    $data = $this->request->all();
    $validator = Validator::make($data, [
      'code' => 'required|string|max:20|unique:hcps',
      'name' => 'required|string|max:45|unique:hcps',
      'address' => 'required|string|max:125',
      'country_id' => 'required|exists:countries,id',
      'state_id' => 'required|exists:states,id',
      'lga_id' => 'required|exists:lgas,id',
      'town_id' => 'exists:towns,id',
      'email' => 'required|email|max:125|unique:hcps',
      'phone' => 'regex:/^\d{7,11}$/|max:15|unique:hcps'
    ]);
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
    $validator = Validator::make($data, [
      'address' => 'string|max:125',
      'name' => 'string|max:45',
      'country_id' => 'exists:countries,id',
      'state_id' => 'exists:states,id',
      'lga_id' => 'exists:lgas,id',
      'town_id' => 'exists:towns,id',
      'code' => [
        'string',
        'max:20',
        Rule::unique('hcps')->ignore($id)
      ],
      'email' => [
        'email',
        'max:125',
        Rule::unique('hcps')->ignore($id)
      ],
      'phone' => [
        'regex:/^\d{7,11}$/',
        'max:15',
        Rule::unique('hcps')->ignore($id)
      ]
    ]);
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
}
