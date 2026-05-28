<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'mobile', 'address', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
