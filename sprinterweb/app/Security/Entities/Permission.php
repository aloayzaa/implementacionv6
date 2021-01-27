<?php

namespace App\Security\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Permission extends Authenticatable
{
    use Notifiable;

    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "permiso";
    public $timestamps = false;

}
