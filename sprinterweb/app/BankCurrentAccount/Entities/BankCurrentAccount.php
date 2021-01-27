<?php

namespace App\BankCurrentAccount\Entities;

use App\Bank\Entities\Bank;
use App\Currency\Entities\Currency;
use Illuminate\Database\Eloquent\Model;

class BankCurrentAccount extends Model
{
    protected $table = "ctactebanco";
    public $timestamps = false;

    public function currency(){
        return $this->belongsTo(Currency::class, 'moneda_id');
    }

    public function banco(){
        return $this->belongsTo(Bank::class);
    }

    public static function cuentaCliente_deBanco($id_banco)
    {
        return static::select('*')
            ->where('banco_id', $id_banco)
            ->get();
    }
    public static function datos_banco(){
        return static::select('*')
            ->from('ctactebanco')
            ->where('estado', 1)
            ->get();
    }

}
