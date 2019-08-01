<?php

namespace App\Models;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class TreatmentUser extends Model
{
  protected $table = 'treatment_user';

  protected $fillable = ['user_id', 'hcp_id','verification_code','verified_by'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function hcp()
  {
    return $this->belongsTo(Hcp::class);
  }



}
