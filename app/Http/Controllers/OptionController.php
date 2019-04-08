<?php

namespace App\Http\Controllers;

use App\Models\Town;
use App\Models\Hcp;

class OptionController extends Controller
{

  public function towns($stateId)
  {
    return response()->json([
      'towns' => Town::where('state_id', $stateId)->get()
    ]);
  }

  public function hcps($key = '', $id = '')
  {
    if(empty($key) && empty($id)){
      return response()->json([
        'hcps' => Hcp::all()
      ]);
    }
    return response()->json([
      'hcps' => Hcp::where($key, $id)->get()
    ]);
  }














}
