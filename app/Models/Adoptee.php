<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adoptee extends Model
{
  protected $fillable = ['user_id', 'adoptee_id'];
  protected $appends = ['adoptor_details'];

  public function adopteeDetails()
  {
    return $this->belongsTo(User::class, 'adoptee_id', 'id');
  }

  public function getAdoptorDetailsAttribute()
  {
    return User::find($this->user_id);
  }

  public function user()
  {
    return $this->belongsTo(User::class,'user_id', 'id');
  }



}
