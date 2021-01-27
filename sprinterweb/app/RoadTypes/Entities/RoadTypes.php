<?php

namespace App\RoadTypes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoadTypes extends Model
{
    protected $table = "tipovia";
    public $timestamps = false;
}
