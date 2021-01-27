<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Str;


class User extends Model implements AuthenticatableContract {

    use Authenticatable;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

  //  const SUPERVISOR = 'gustavo@example.com'; //ya no se usa

    public $timestamps = true;

    protected $fillable = ['name', 'phone', 'email', 'password', 'state'];

    public function companies(){
        return $this->hasMany(Company::class);
    }

    public static function generaVerificationToken(){
        return Str::random(40);
    }

}
