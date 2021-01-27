<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $timestamps = false;

    const RENUEVA_SUSCRIPCION = '1';
    const NO_RENUEVA_SUSCRIPCION = '0';

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function plan(){
        return $this->belongsTo(Plan::class);
    }

}
