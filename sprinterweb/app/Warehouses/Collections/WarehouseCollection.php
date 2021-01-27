<?php

namespace App\WareHouses\Collections;


class WarehouseCollection
{

    public function actions($warehouses)
    {
        foreach ($warehouses as $warehouse) {
            $warehouse->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.warehouses', ['id' => $warehouse->id]) . '" class="">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($warehouse->estado == 1) {
                $warehouse->actions .='<li class="list-dropdown-actions">
                                                <a onclick="procesar_actualizar(' . $warehouse->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                            </li>';
            } else {
                $warehouse->actions .='<li class="list-dropdown-actions">
                                                <a onclick="procesar_actualizar(' . $warehouse->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                            </li>';
            }
            $warehouse->actions .= '</ul> </div> </div>';


            if ($warehouse->estado == 1) {
                $warehouse->estado = '<span class="label label-success">Activo</span>';
            } else {
                $warehouse->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
