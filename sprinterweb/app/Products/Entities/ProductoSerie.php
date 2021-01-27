<?php

namespace App\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductoSerie extends Model //Este modelo no tiene tabla en la BD
{
    protected $table = "productoserie";

    public function busquedacmd($fecha, $almacen, $sql, $venta) { // Clasic, clase: productoserie, método busquedacmd
        //dd($fecha . ' / '. $almacen . ' / '. $sql . ' / '. $venta);
        $lcTipo = 'S';
        return DB::select('CALL getpdtobusqueda_lote(?,?,?,?)', array($fecha, $almacen, $sql, $lcTipo));
    }
}
