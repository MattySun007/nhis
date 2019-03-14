<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionUser extends Model
{
  protected $table = 'institution_user';

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function institution()
  {
    return $this->belongsTo(Institution::class);
  }
}
