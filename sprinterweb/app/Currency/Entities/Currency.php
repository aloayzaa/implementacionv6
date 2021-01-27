<?php

namespace App\Currency\Entities;

use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = "moneda";
    public $timestamps = false;

    public function documentToPay()
    {
        return $this->hasMany(DocumentToPay::class);
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
