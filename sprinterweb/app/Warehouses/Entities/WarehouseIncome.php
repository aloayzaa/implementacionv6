<?php

namespace App\Warehouses\Entities;

use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\DocumentToPay\Entities\DocumentToPay;
use App\MovementTypes\Entities\MovementType;
use App\Period\Entities\Period;
use App\PurchaseOrder\Entities\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class WarehouseIncome extends Model
{
    protected $table = "ingresoalmacen";
    public $timestamps = false;


    public function docxpagar(){
        return $this->belongsTo(DocumentToPay::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'moneda_id');
    }

    public function movimientotipo(){
        return $this->belongsTo(MovementType::class)->withDefault();
    }

    public function tercero(){
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function periodo(){
        return $this->belongsTo(Period::class);
    }

    public function ordencompra(){
        return $this->belongsTo(PurchaseOrder::class);
    }


    public function scopeListExit($query, $tipo, $period_id){
        if($tipo){
            return $query->select(        //quite un * para ingreso y sucursal
                'i.id as ingreso_id',
                'i.estado as estado_ingreso',
                'i.numero',
                'i.fecha',
                'i.totalmn',
                'i.totalme',
                'i.glosa',
                'a.codigo as codigo_almacen',
                'a.descripcion as descripcion_almacen',
                't.codigo as codigo_tercero',
                't.descripcion as descripcion_tercero',
                'mov.codigo as codigo_movimiento',
                'mov.descripcion as descripcion_movimiento',
                'm.simbolo as simbolo_moneda')

                ->from('ingresoalmacen as i')
                ->leftjoin('almacen as a','a.id','=','i.almacen_id')
                ->leftjoin('tercero as t','t.id','=','i.tercero_id')
                ->leftjoin('movimientotipo as mov','mov.id','=','i.movimientotipo_id')
                ->leftjoin('moneda as m','m.id','=','i.moneda_id')
                ->where('i.tipo',$tipo)
                ->where('i.periodo_id',$period_id)
                ->get();
        }
    }

    public static function data_ordencompra($ordencompra_id){
        return static::select('i.*',DB::raw("concat('IA ', p.codigo,'-',right(concat('00000',i.numero),5)) as referencia"))
        ->from('ingresoalmacen as i')
        ->join('periodo as p','i.periodo_id','=','p.id')
        ->where('i.ordencompra_id',$ordencompra_id)
        ->get();
    }

    public static function historial_precio($producto_id){

        return DB::select("select i.fecha, i.refprovision, t.codigo as ter_cod, t.descripcion as razonsocial,
		convert(case when ifnull(o.id,0)=0 then concat('IN ', pe.codigo, '-', right(concat('00000', i.numero),5)) else
			concat('OC ', pc.codigo, '-', right(concat('00000', o.numero),5)) end using latin1) as numero,
		p.codigo, p.descripcion, case when ifnull(o.id,0)=0 then u.codigo else uc.codigo end as umedida, d.cantidad,
		case when ifnull(o.id,0) = 0 then d.preciomn else case when mo.tipo = 'N' then od.precio else od.precio * o.tcambio end end as preciomn,
		case when ifnull(o.id,0) = 0 then d.preciome else case when mo.tipo = 'N' then od.precio / o.tcambio else od.precio end end as preciome,
		case when ifnull(o.id,0) = 0 then d.importemn else case when mo.tipo = 'N' then od.importe else od.importe * o.tcambio end end as importemn,
		case when ifnull(o.id,0) = 0 then d.importeme else case when mo.tipo = 'N' then od.importe / o.tcambio else od.importe end end as importeme,
		i.ventana, i.id
        from ingresoalmacen_detalle d
        join ingresoalmacen i on d.parent_id = i.id
        left join ordencompra o on i.ordencompra_id = o.id
        left join ordencompra_detalle od on od.parent_id = o.id and d.producto_id = od.producto_id
        join producto p on d.producto_id = p.id
        join tercero t on i.tercero_id = t.id
        join movimientotipo m on i.movimientotipo_id = m.id
        join umedida u on d.umedida_id = u.id
        left join umedida uc on od.umedida_id = uc.id
        join periodo pe on i.periodo_id = pe.id
        left join periodo pc on o.periodo_id = pc.id
        join moneda mo on i.moneda_id = mo.id
        where p.id = $producto_id and m.grupo = 'C' and i.tipo = 'I'
        order by i.fecha desc");

    }

    public static function historial_precio_oc($producto_id){
        return DB::select("select i.fecha, i.refprovision, t.codigo as ter_cod, t.descripcion as razonsocial,
		convert(case when ifnull(o.id,0)=0 then concat('IN ', pe.codigo, '-', right(concat('00000', i.numero),5)) else
			concat('OC ', pc.codigo, '-', right(concat('00000', o.numero),5)) end using latin1) as numero,
		p.codigo, p.descripcion, case when ifnull(o.id,0)=0 then u.codigo else uc.codigo end as umedida, d.cantidad,
		case when ifnull(o.id,0) = 0 then d.preciomn else case when mo.tipo = 'N' then od.precio else od.precio * o.tcambio end end as preciomn,
		case when ifnull(o.id,0) = 0 then d.preciome else case when mo.tipo = 'N' then od.precio / o.tcambio else od.precio end end as preciome,
		case when ifnull(o.id,0) = 0 then d.importemn else case when mo.tipo = 'N' then od.importe else od.importe * o.tcambio end end as importemn,
		case when ifnull(o.id,0) = 0 then d.importeme else case when mo.tipo = 'N' then od.importe / o.tcambio else od.importe end end as importeme,
		i.ventana, i.id
        from ingresoalmacen_detalle d
        join ingresoalmacen i on d.parent_id = i.id
        join ordencompra o on i.ordencompra_id = o.id
        join ordencompra_detalle od on od.parent_id = o.id and d.producto_id = od.producto_id
        join producto p on d.producto_id = p.id
        join tercero t on i.tercero_id = t.id
        join movimientotipo m on i.movimientotipo_id = m.id
        join umedida u on d.umedida_id = u.id
        left join umedida uc on od.umedida_id = uc.id
        join periodo pe on i.periodo_id = pe.id
        left join periodo pc on o.periodo_id = pc.id
        join moneda mo on i.moneda_id = mo.id
        where p.id = $producto_id and m.grupo = 'C' and i.tipo = 'I'
        order by i.fecha desc limit 1");
    }

    public function ScopeExistencias($query, $id){
        return DB::select("select d.referencia_id, concat(dc.codigo, ' ', r.seriedoc, '-', r.numerodoc) documento, r.fechadoc, m.simbolo moneda, r.glosa, r.total,
		ifnull(e.parent_id,0) parent_id, ifnull(e.importe,0) importe
		from docxpagar_detalle d
		join docxpagar c on d.parent_id = c.id
		join docxpagar r on d.referencia_id = r.id
		join pcg p on d.cuenta_id = p.id
		join documentocom dc on r.documento_id = dc.id
		join moneda m on r.moneda_id = m.id
		left join ingresoalmacen_exr e on e.docxpagar_id = r.id
		where c.ordencompra_id = $id and d.referencia_id is not null and d.esnuevo = 1 and p.codigo like '28%' and c.estado <> 'ANULADO'");
    }

    public static function orden_compra($pId){
        return static::selectRaw("m.fecha, convert(concat('IA ', p.codigo, '-', right(concat('00000', m.numero),5)) using latin1) as documento, m.glosa, 
		m.totalmn, m.totalme, m.refgremision, m.refprovision, m.id, m.ventana")
            ->from('ingresoalmacen as m')
            ->join('periodo as p', 'm.periodo_id', '=', 'p.id')
            ->whereRaw("m.ordencompra_id =".$pId." and m.estado <> 'ANULADO'")
            ->get();
    }

    public static function pedido_almacen($pId){
        return static::selectRaw("i.*, case when i.tipo = 'I' then 'IN' else 'SA' end as tipodoc, p.codigo as periodo, m.grupo")
            ->from('ingresoalmacen as i')
            ->join('periodo as p', 'i.periodo_id', '=', 'p.id')
            ->join('movimientotipo as m', 'i.movimientotipo_id', '=', 'm.id')
            ->whereRaw("i.pedidoalmacen_id =".$pId." and i.estado <> 'ANULADO'")
            ->get();
    }

    public static function documento_pagar($pId){
        return static::selectRaw("m.*, 'IN' as tipodoc, p.codigo as periodo")
            ->from('ingresoalmacen as m')
            ->join('periodo as p', 'm.periodo_id', '=', 'p.id')
            ->whereRaw("m.docxpagar_id =".$pId." and m.estado <> 'ANULADO'")
            ->get();
    }

    public static function ingreso_transferencia($pId){
        return static::selectRaw("m.*, 'IN' as tipodoc, p.codigo as periodo")
            ->from('ingresoalmacen as m')
            ->join('periodo as p', 'm.periodo_id', '=', 'p.id')
            ->whereRaw("m.ingresoalmacen_id =".$pId." and m.estado <> 'ANULADO'")
            ->get();
    }

    public static function orden_trabajo($pId){
        return static::selectRaw("m.*, 'IN' as tipodoc, p.codigo as seriedoc")
            ->from('ingresoalmacen as m')
            ->join('periodo as p', 'm.periodo_id', '=', 'p.id')
            ->whereRaw("m.ordentrabajo_id =".$pId." and m.estado <> 'ANULADO'")
            ->get();
    }

    public static function ingreso_orden_trabajo($pId){
        return static::selectRaw("i.*, 'IN' as tipodoc, pe.codigo as seriedoc, d.producto_id, p.codigo, p.descripcion, SUM(d.cantidad) cantidad, SUM(d.importe) importe")
            ->from('ingresoalmacen as i')
            ->join('ingresoalmacen_detalle as d', 'd.parent_id', '=', 'i.id')
            ->join('producto as p', 'd.producto_id', '=', 'p.id')
            ->join('periodo as pe', 'i.periodo_id', '=', 'pe.id')
            ->join('movimientotipo as m', 'i.movimientotipo_id', '=', 'm.id')
            ->whereRaw("i.ordentrabajo_id =".$pId." and i.tipo = 'I' and i.estado <> 'ANULADO' and m.grupo = 'R'")
            ->groupBy('i.id', 'd.producto_id')
            ->get();
    }

    public static function selectidcmd($pId){
        return static::selectRaw("i.*, p.codigo as periodo, mv.grupo as grupomov, m.tipo as tipomoneda")
            ->from("ingresoalmacen as i")
            ->join("periodo as p", 'i.periodo_id', '=', 'p.id')
            ->join('movimientotipo as mv', 'i.movimientotipo_id', '=', 'mv.id')
            ->join('moneda as m', 'i.moneda_id', '=', 'm.id')
            ->where('i.id', $pId)
            ->first();
    }

    public static function selectrefcmd_x($docxpagar_id){ //clasic, clase: ingresoalmacen, métdo: selectrefcmd - solo para el parametro X 
        return static::select(DB::raw("m.*, 'IN' as tipodoc, p.codigo as periodo"))
        ->from("ingresoalmacen as m")
        ->join("periodo as p", "m.periodo_id", "=", "p.id")
        ->where("m.docxpagar_id", $docxpagar_id)
        ->where("m.estado", "<>", 'ANULADO')
        ->get();
    }

    public static function ultimonumero($seriedoc, $tipo){

        return static::select(DB::raw("max(cast(numerodoc as decimal(11,0))) as numerodoc"))
        ->where('seriedoc', $seriedoc)
        ->where('tipo', $tipo)
        ->first();


    }
    
    public static function validadoc($seriedoc, $numerodoc, $tipo){

        return static::select("id")
        ->where("seriedoc", $seriedoc)
        ->where("numerodoc", $numerodoc)
        ->where("tipo", $tipo)
        ->first();

    }
    
    public static function referencia_salidaalmacen_facturacion($docxpagar_id){

        return static::select(DB::raw("i.id, i.numero, i.seriedoc, i.numerodoc, p.codigo as periodo, i.ventana"))
        ->from("ingresoalmacen as i")
        ->join("periodo as p", "i.periodo_id", "=", "p.id")
        ->where("i.docxpagar_id", $docxpagar_id)
        ->first();

    }

}
