<?php

namespace App\Models;

use App\Traits\InsertOnDuplicateKey;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
  use InsertOnDuplicateKey;
  protected $fillable = ['batch_code', 'user_id', 'amount', 'month', 'year', 'approved', 'approved_by', 'approved_at', 'created_at', 'updated_at', 'processed', 'processed_by', 'processed_at'];

  public function user()
  {
    return $this->belongsTo(User::class,'user_id', 'id');
  }
}
