<?php

namespace App\Version\Entities;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "version";
    public $timestamps = false;

    public static function getListaCbo()
    {
        return static::select('*')
            ->orderby('codigo', 'asc')
            ->get();
    }
}
