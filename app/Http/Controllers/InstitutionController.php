<?php

namespace App\Http\Controllers;

use App\Utilities\Utility;
use Log;
use App\Models\HcpInstitution;
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
      'institutions' => auth()->user()->user_type == 'Institution User' ? Institution::whereIn('id', auth()->user()->user_institutions)->with(['town', 'country', 'state', 'lga'])->get()->all() : Institution::with(['town', 'country', 'state', 'lga'])->get()->all(),
      'countries' => Country::all(),
      'states' => State::all(),
      'lgas' => Lga::all()
    ]);
  }

  public function generateInstitutionCode()
  {
    $seed = "INST-";
    a:
    $code = Utility::generateRandomNumber(6);
    $code = $seed.$code;
    if(Institution::where('code', $code)->count() <= 0){
      return $code;
    }else{
      goto a;
    }
  }

  public function getInstitutionCode()
  {
    return response()->json([
      'success' => true,
      'message' => 'Institution code generated',
      'data' => array('code' => $this->generateInstitutionCode())
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
    $inst = new Institution($data);
    $inst->save();
    $inst->loadMissing(['town', 'country', 'state', 'lga']);
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
    $inst->fill($data);
    $inst->save();
    $inst->loadMissing(['town', 'country', 'state', 'lga']);
    return response()->json([
      'success' => true,
      'message' => 'Institution updated',
      'data' => $inst
    ]);
  }

  public function deleteHcp($id)
  {
    $hcp_inst = HcpInstitution::find($id);
    $institution = Institution::find($hcp_inst['institution_id']);
    if (empty($institution))
    {
      return response()->json([
        'success' => false,
        'message' => 'Institution not found',
      ], 400);
    }
    HcpInstitution::destroy(array($id));
    $hcp = $institution->hcps()->with(['hcp', 'hcp.town', 'hcp.state', 'hcp.country', 'hcp.lga', 'hcp.hcp_type', 'hcp.bank'])->get()->all();
    return response()->json([
      'success' => true,
      'message' => 'Institution Hcp deleted',
      'data' => $hcp
    ]);
  }

  public function listHcp($id = "")
  {
    if(!empty($id)){
      $inst = Institution::find($id);
      $hcp = $inst->hcps()->with(['hcp', 'hcp.town', 'hcp.state', 'hcp.country', 'hcp.lga', 'hcp.hcp_type', 'hcp.bank'])->get()->all();
      return response()->json([
        'success' => true,
        'message' => 'Institution Hcp listed',
        'data' => $hcp
      ]);
    }
    return view('institutions.list-hcps', [
      'pageTitle' => 'Institution Hcp',
    ]);
  }

  public function search($str)
  {
    $inst = Institution::where('code', $str)
      ->orWhere('address', 'LIKE', '%'.$str.'%')
      ->orWhere('rcc_number', 'LIKE', '%'.$str.'%')
      ->orWhere('name', 'LIKE', '%'.$str.'%')
      ->orWhere('email', 'LIKE', '%'.$str.'%')
      ->orWhere('phone', 'LIKE', '%'.$str.'%')
      ->with(['town', 'country', 'state', 'lga'])->get()->all();
    if($inst){
      return response()->json([
        'success' => true,
        'message' => 'Records returned',
        'data' => $inst
      ], 200);
    }else{
      return response()->json([
        'success' => false,
        'message' => 'No record found!',
        'data' => array()
      ], 200);
    }
  }

  public function institutionHcps($id)
  {
    $institution = Institution::find($id);
    return view('institutions.list-hcp', [
      'pageTitle' => 'Institution Hcp',
      'institution' => $institution,
      'hcps' => $institution->hcps()->with(['hcp', 'hcp.town', 'hcp.state', 'hcp.country', 'hcp.lga', 'hcp.hcp_type', 'hcp.bank'])->get()->all()
    ]);
  }

  public function addHcp()
  {
    $data = $this->request->all();
    $institution = Institution::find($data['institution_id']);
    if (empty($institution))
    {
      return response()->json([
        'success' => false,
        'message' => 'Institution not found',
      ], 400);
    }
    $validator = HcpInstitution::creationValidator($data);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }
    HcpInstitution::create($data);
    $hcp = $institution->hcps()->with(['hcp', 'hcp.town', 'hcp.state', 'hcp.country', 'hcp.lga', 'hcp.hcp_type', 'hcp.bank'])->get()->all();
    return response()->json([
      'success' => true,
      'message' => 'Institution Hcp created',
      'data' => $hcp
    ]);
  }






}
