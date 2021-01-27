<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class SearchPdtoController extends Controller // clasic, formulario : busquedapdto
{
    protected $tabla;
    protected $nombre_tabla;
    protected $valor;
    protected $where;
    protected $almacen;
    protected $fecha;
    protected $venta;

    public function __construct(Model $tabla, $valor, $where, $almacen, $fecha, $venta)
    {
        $this->tabla = $tabla;
        $this->nombre_tabla = $this->tabla->getTable();
        $this->valor = $valor; // dependiendo : producto codigo, serie producto
        $this->where = $where;
        $this->almacen = $almacen;
        $this->fecha = $fecha;
        $this->venta = $venta; // clasic propiedad nventa
    }

    public function codigo_valid($codigo){  // objeto : txtcodigo, método: Valid

        $codigo = str_replace(" ", '%', '%'.$codigo); // %a%b%c Los porcentajes intermedios equivalen a espacios en blancos

        switch ($this->nombre_tabla){
            case 'productoserie' :
                $sql = $this->sql_productoserie($codigo);
            break;
            case 'productolote' :
                $sql = $this->sql_productolote();
            break;
            default:
                $sql = $this->sql_producto($codigo);
            break;
        }

        if (!empty($this->where)){
            $sql .= " and $this->where";
        }

        return $this->busquedacmd($sql);
    }

    public function descripcion_valid($descripcion, $presentacion){ // objeto : txtdescripcion, método: Valid

        $descripcion = str_replace(" ", '%', '%'.$descripcion); // %a%b%c Los porcentajes intermedios equivalen a espacios en blancos

        $sql = "p.descripcion like '$descripcion%'";

        if (!empty($presentacion)) {
            $presentacion = str_replace(" ", '%', '%'.$presentacion);
            $sql .= " and (p.caracteristicas like '$presentacion%' or m.descripcion like '$presentacion%')";
        }

        if (!empty($this->where)){
            $sql .= " and $this->where";
        }

        return $this->busquedacmd($sql);
    }

    public function presentacion_valid($presentacion, $descripcion){ // objeto : txtpresentacion, método: Valid

        $presentacion = str_replace(" ", '%', '%'.$presentacion);

        $sql = " (p.caracteristicas like '$presentacion%' or m.descripcion like '$presentacion%')";

        if (!empty($descripcion)){
            $descripcion = str_replace(" ", '%', '%'.$descripcion);
            $sql .= " and p.descripcion like '$descripcion%'";
        }

        if (!empty($this->where)){
            $sql .= " and $this->where";
        }

        return $this->busquedacmd($sql);
    }

    private function sql_producto($codigo){
        return "(p.codigo like '$codigo%' or p.ean like '$codigo%' or p.cumseps like '$codigo%')";
    }

    private function sql_productoserie($codigo){
        return "d.serie like '$codigo%'";
    }

    private function busquedacmd($sql) { // clase: producto, método busquedacmd
        // dd($this->fecha . ' / '. $this->almacen . ' / '. $sql . ' / '. $this->venta);
        return $this->tabla->busquedacmd($this->fecha, $this->almacen, $sql, $this->venta);
    }
}
