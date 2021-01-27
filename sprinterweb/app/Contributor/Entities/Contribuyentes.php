<?php

namespace App\Contributor\Entities;

use Illuminate\Database\Eloquent\Model;

class Contribuyentes extends Model
{
    protected $connection = 'DB_CONNECTION_SERVICIOS';
    protected $table = "empresa";
    public $timestamps = false;
}
