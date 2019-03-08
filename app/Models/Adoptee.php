<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adoptee extends Model
{

    public function adoptor()
    {
        return $this->belongsTo(User::class);
    }
}
