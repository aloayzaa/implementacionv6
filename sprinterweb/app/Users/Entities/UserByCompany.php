<?php

namespace App\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class UserByCompany extends Model implements AuthenticatableContract {

    use Authenticatable;

    protected $table = "usuario";
    public $timestamps = false;
}
