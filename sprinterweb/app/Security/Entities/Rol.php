<?php

namespace App\Security\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Rol extends Authenticatable
{
    use Notifiable;

    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "rol";
    public $timestamps = false;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'rol_permiso', 'rolid', 'permisoid');
    }

}
