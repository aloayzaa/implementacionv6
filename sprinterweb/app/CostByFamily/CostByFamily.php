<?php

namespace App\CostByFamily;

use App\Costs\Entities\Costs;
use App\Families\Entities\Familia;
use Illuminate\Database\Eloquent\Model;

class CostByFamily extends Model
{
    protected $table = "ccostoxfamilia";
    public $timestamps = false;

    //no quitar

    public function families(){
        return $this->hasMany(Familia::class);
    }

    public function costs(){
        return $this->hasMany(Costs::class);
    }


}
