<?php

namespace App\AccountingTax\Entities;

use App\AccountingPlan\Entities\AccountingPlan;
use Illuminate\Database\Eloquent\Model;

class AccountingTax extends Model
{
    protected $table = "impuesto_contab";
    public $timestamps = false;

    public static function editarRefactorizado($id, $item, $data)
    {
        return static::where('parent_id', $id)
            ->where('item', $item)
            ->update($data);
    }

    public function cuentamn()
    {
        return $this->belongsTo(AccountingPlan::class, 'cuentamn_id');
    }

    public function cuentame()
    {
        return $this->belongsTo(AccountingPlan::class, 'cuentame_id');
    }
}
