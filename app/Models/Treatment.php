<?php

namespace App\Models;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
  protected $table = 'treatments';

  protected $fillable = ['user_id', 'hcp_id','verification_code','code','bill','medical_officer','diagnosis','medical_condition','medication_administered','created_by','updated_by','paid','paid_at','refer_hcp_id'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function hcp()
  {
    return $this->belongsTo(Hcp::class);
  }

  public static function creationValidator(array $data)
  {
    return Validator::make($data, [
      'code' => 'required|string|max:20|unique:treatments',
      'medical_officer' => 'required|string',
      'bill' => 'numeric',
      'user_id' => 'required|exists:users,id',
      'hcp_id' => 'required|exists:hcps,id',
      'medication_administered' => 'required|string',
      'medical_condition' => 'required|string'
    ]);
  }

  public static function updateValidator(array $data, $id)
  {
    return Validator::make($data, [
      'medical_officer' => 'required|string',
      'bill' => 'numeric',
      'user_id' => 'required|exists:users,id',
      'hcp_id' => 'required|exists:hcps,id',
      'medication_administered' => 'required|string',
      'medical_condition' => 'required|string',
      'code' => [
        'string',
        'max:20',
        Rule::unique('treatments')->ignore($id)
      ]
    ]);
  }

  public static function getTreatments($data)
  {
    /*if(is_array($data['hcp_id']) && count($data['hcp_id']) >= 1){
      if(isset($data['paid']) && is_numeric($data['paid'])){
        return Treatment::where('paid', $data['paid'])->whereIn('hcp_id', $data['hcp_id'])->with(['user', 'hcp'])->get()->all();
      }
      return Treatment::whereIn('hcp_id', $data['hcp_id'])->with(['user', 'hcp'])->get()->all();
    }
    if(is_array($data['institution_id']) && count($data['institution_id']) >= 1){
      if(isset($data['paid']) && is_numeric($data['paid'])){
        return Treatment::where('paid', $data['paid'])->whereIn('hcp_id', $data['hcp_id'])->with(['user', 'hcp'])->get()->all();
      }
      return Treatment::whereIn('hcp_id', $data['hcp_id'])->with(['user', 'hcp'])->get()->all();
    }*/
    if(isset($data['hcp_id']) && is_numeric($data['hcp_id'])){
      if(isset($data['paid']) && is_numeric($data['paid'])){
        return Treatment::where('paid', $data['paid'])->where('hcp_id', $data['hcp_id'])->with(['user', 'hcp'])->get()->all();
      }
      return Treatment::where('hcp_id', $data['hcp_id'])->with(['user', 'hcp'])->get()->all();
    }

    if(isset($data['institution_id']) && is_numeric($data['institution_id'])){
      if(isset($data['paid']) && is_numeric($data['paid'])){
        return Treatment::where('paid', $data['paid'])->whereIn('hcp_id', HcpInstitution::where('institution_id', $data['institution_id'])->pluck('hcp_id'))->with(['user', 'hcp'])->get()->all();
      }
      return Treatment::whereIn('hcp_id', HcpInstitution::where('institution_id', $data['institution_id'])->pluck('hcp_id'))->with(['user', 'hcp'])->get()->all();
    }

    if(isset($data['user_id']) && is_numeric($data['user_id'])){
      return Treatment::where('user_id',  $data['user_id'])->with(['user', 'hcp'])->get()->all();
    }

    return Treatment::where('user_id',  auth()->user()->id)->with(['user', 'hcp'])->get()->all();

  }

}
