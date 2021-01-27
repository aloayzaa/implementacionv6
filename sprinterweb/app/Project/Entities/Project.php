<?php

namespace App\Project\Entities;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = "proyecto";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
