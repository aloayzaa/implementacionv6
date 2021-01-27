<?php

namespace App\Message\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Message extends Model
{
    protected $table = "mensaje";
    public $timestamps = false;

}
