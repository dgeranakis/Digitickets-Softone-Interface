<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'operating_system', 'browser', 'device_type', 'signin', 'signout'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
