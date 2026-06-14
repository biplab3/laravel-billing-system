<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //

    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'email',
        'gst_no',
        'address',
    ];
}
