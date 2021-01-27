<?php

namespace App\Security\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PermissonRol extends Authenticatable
{
    use Notifiable;

    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "rol_permiso";
    public $timestamps = false;
}
