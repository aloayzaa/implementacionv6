<?php

namespace App\Quotation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuotationDetail extends Model
{
    
    protected $table = "cotizacion_detalle";
    protected $primaryKey = "parent_id";
    public $timestamps = false;

    public static function selectidcmd($parent_id){ // ver clasic

        return static::select(DB::raw("r.sucursal_id, d.producto_id, d.umedida_id, r.tercero_id,
        p.codigo, p.descripcion, p.esafecto, p.consumo, p.aumentovta, p.tipoproducto, d.*, cast(0 as decimal(15,6)) as stock,
        p.marca, p.modelo, u.codigo as ume_cod, a.alquiler, a.opcion, ifnull(ma.descripcion,'') as mca_dsc, ifnull(mo.descripcion,'') as mod_dsc,
        p.umedida_id as um1, p.ucompra_id as um2, p.tipoproducto, ifnull(um.factor, p.conversion) as conversion,
        ifnull(f.precio, p.precio1) as precio1, ifnull(f.precio2, p.precio2) as precio2, ifnull(f.precio3, p.precio3) as precio3, 
        ifnull(f.precio4, p.precio4) as precio4, ifnull(f.precio5, p.precio5) as precio5, ifnull(f.precio6, p.precio6) as precio6, p.talla,
        ifnull(pt.codigo,'') as clicodigo, ifnull(pt.descripcion,'') as clidescripcion, ifnull(tp.codigo,'') as dis_cod, ifnull(tp.descripcion,'') as dis_dsc"))
        ->from("cotizacion_detalle as d")
        ->join("cotizacion as r", "d.parent_id", "=", "r.id")
        ->leftJoin("cotizacion_adicional as a", "a.parent_id", "=", "d.parent_id")
        ->join("producto as p", "d.producto_id", "=", "p.id") 
        ->leftJoin("producto_tercero as pt", "d.prdtercero_id", "=", "pt.id")
        ->leftJoin("marca as ma", "p.marca_id", "=", "ma.id")
        ->leftJoin("modelo as mo", "p.modelo_id", "=", "mo.id")	
        ->leftJoin("tarifario as f", "f.sucursal_id", "=", DB::raw("r.sucursal_id and f.producto_id = d.producto_id and f.umedida_id = d.umedida_id and ifnull(f.cliente_id,r.tercero_id) = r.tercero_id and r.contacto_item = ifnull(f.ruta_id,0)"))
        ->leftJoin("umedida_conversion as um", "um.parent_id", "=", DB::raw("p.umedida_id and um.umedida_id = d.umedida_id"))
        ->leftJoin("tipodisponible as tp", "d.tipodisponible_id", "=", "tp.id")
        ->join("umedida as u", "d.umedida_id", "=", "u.id") 
        ->where("d.parent_id", $parent_id)
        ->get();        


    } 
    

}
