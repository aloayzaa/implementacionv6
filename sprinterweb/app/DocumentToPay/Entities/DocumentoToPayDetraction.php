<?php

namespace App\DocumentToPay\Entities;

use App\DetractionType\Entities\DetractionType;
use Illuminate\Database\Eloquent\Model;

class DocumentoToPayDetraction extends Model
{
    protected $table = "docxpagar_detraccion";
    protected $primaryKey = 'parent_id';
    public $timestamps = false;

    public function tipoDetraccion()
    {
        return $this->belongsTo(DetractionType::class, 'tipodetraccion_id');
    }

    public static function updateDetraccion($id, $data)
    {
        return static::where('parent_id', $id)
            ->update($data);
    }

    public static function editarRefactorizado($id, $item, $data)
    {
        return static::where('parent_id', $id)
            ->where('item', $item)
            ->update($data);
    }

    public static function selectparentcmd($parent_id){ // clasic, clase: docxpagar_detraccion, método: selectparentcmd
        // solo debe a ver un item por documento
        return static::select('*')
        ->where('parent_id', $parent_id)
        ->first();

    }
}
