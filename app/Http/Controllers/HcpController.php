<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Log;
use App\Utilities\Utility;
use App\Models\Hcp;
use App\Models\Country;
use App\Models\HcpType;
use App\Models\State;
use App\Models\Lga;

class HcpController extends Controller
{

  public function index()
  {
    return view('hcps.list', [
      'pageTitle' => 'HCPs',
      'hcps' => auth()->user()->user_type == 'Hcp User' ? Hcp::whereIn('id', auth()->user()->user_hcps)->with(['town', 'country', 'state', 'lga', 'hcp_type', 'bank'])->get()->all() : Hcp::with(['town', 'country', 'state', 'lga', 'hcp_type', 'bank'])->get()->all(),
      'countries' => Country::all(),
      'hcp_types' => HcpType::all(),
      'states' => State::all(),
      'lgas' => Lga::all(),
      'banks' => Bank::all()
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
    $hcp = new Hcp($data);
    $hcp->save();
    $hcp->loadMissing(['town', 'country', 'state', 'lga', 'hcp_type', 'bank']);
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
    $hcp->fill($data);
    $hcp->save();
    $hcp->loadMissing(['town', 'country', 'state', 'lga', 'hcp_type', 'bank']);
    return response()->json([
      'success' => true,
      'message' => 'Hcp updated',
      'data' => $hcp
    ]);
  }

  public function generateHcpCode()
  {
    $seed = "HCP-";
    a:
    $code = Utility::generateRandomNumber(6);
    $code = $seed.$code;
    if(Hcp::where('code', $code)->count() <= 0){
      return $code;
    }else{
      goto a;
    }
  }

  public function getHcpCode()
  {
    return response()->json([
      'success' => true,
      'message' => 'Hcp code generated',
      'data' => array('code' => $this->generateHcpCode())
    ]);
  }





}
