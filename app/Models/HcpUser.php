<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcpUser extends Model
{
    protected $table = 'hcp_user';

    protected $fillable = ['user_id', 'hcp_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hcp()
    {
        return $this->belongsTo(Hcp::class);
    }
}
