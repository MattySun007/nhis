<?php

namespace App\Http\Controllers;

use Log;
use App\Utilities\Utility;
use App\Models\Hcp;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;

class TreatmentController extends Controller
{

  public function index($id)
  {
    return response()->json([
      'pageTitle' => 'HCP Treatment',
      'hcp' => Hcp::where('id', $id)->with(['town', 'country', 'state', 'lga', 'hcp_type', 'bank'])->first(),
      'treatments' => Treatment::getTreatments(array('hcp_id' => $id)),
      'treatments_plain' => DB::table('treatments')
        ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
        ->join('users', 'treatments.user_id', '=', 'users.id')
        ->select('treatments.*','users.verification_no')
        ->get()
    ]);
  }

  public function create()
  {
    $data = $this->request->all();
    $validator = Treatment::creationValidator($data);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }
    $treatment = new Treatment($data);
    $treatment->save();
    $treatment->loadMissing(['user', 'hcp']);
    return response()->json([
      'success' => true,
      'message' => 'Treatment created',
      'data' => $treatment
    ]);
  }

  public function update($id)
  {
    $data = $this->request->all();
    $validator = Treatment::updateValidator($data, $id);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }
    $treatment = Treatment::find($id);
    $treatment->fill($data);
    $treatment->save();
    $treatment->loadMissing(['user', 'hcp']);
    return response()->json([
      'success' => true,
      'message' => 'Treatment updated',
      'data' => $treatment
    ]);
  }

  public function deleteTreatment($id)
  {
    $t = Treatment::find($id);
    if (empty($t))
    {
      return response()->json([
        'success' => false,
        'message' => 'Treatment not found',
      ], 400);
    }
    return response()->json([
      'success' => true,
      'message' => 'Treatment deleted',
      'data' => []
    ], 200);
  }

  public function generateTreatmentCode($code)
  {
    $seed = "TRT-$code-";
    a:
    $code = Utility::generateRandomNumber(6);
    $code = $seed.$code;
    if(Treatment::where('code', $code)->count() <= 0){
      return $code;
    }else{
      goto a;
    }
  }

  public function getTreatmentCode($code)
  {
    // TRT-028478-477181 => after, TRT, THE NEXT CODE IS the HCP CODE WITHOUT HCP-
    $code = str_replace('HCP-','',$code);
    return response()->json([
      'success' => true,
      'message' => 'Treatment code generated',
      'data' => array('code' => $this->generateTreatmentCode($code))
    ]);
  }






}
