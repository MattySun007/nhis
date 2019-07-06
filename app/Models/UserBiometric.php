<?php

namespace App\Models;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UserBiometric extends Model
{
  protected $table = 'user_biometrics';

  protected $fillable = ['user_id', 'class_id', 'data_url', 'biometric_status', 'token_id'];

  public function user(){
    return $this->belongsTo(User::class);
  }

  public static function creationValidator(array $data){
    return Validator::make($data, [
      'class_id' => 'required|string|max:255|unique:user_biometrics',
      'user_id' => 'required|integer|min:1|exists:users,id',
    ]);
  }

  public static function updateValidator(array $data, $id){
    return Validator::make($data, [
      'class_id' => ['string', 'max:255', Rule::unique('user_biometrics')->ignore($id)],
      'user_id' => ['required', Rule::unique('user_biometrics')->ignore($id)],
      //'user_id' => 'required|integer|min:1|exists:users,id|unique:user_biometrics',
    ]);
  }

  public static function getUserBiometricData($where){
    return UserBiometric::where($where)->with(['user'])->first();
  }



}
