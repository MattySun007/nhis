<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Illuminate\Validation\Rule;

class HcpInstitution extends Model
{
  protected $table = 'hcp_institution';

  protected $fillable = ['institution_id', 'hcp_id'];

  public function institution()
  {
    return $this->belongsTo(Institution::class);
  }

  public function hcp()
  {
    return $this->belongsTo(Hcp::class);
  }

  public static function creationValidator(array $data)
  {
    $rules = [
      'institution_id' => 'required|numeric',
      'hcp_id' => 'required|numeric',
    ];
    return Validator::make($data, $rules);
  }





}
