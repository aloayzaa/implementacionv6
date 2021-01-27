<?php


namespace App\Taxes\Collections;

class TaxesCollection
{
    public function actions($taxes)
    {
        foreach ($taxes as $tax) {
            $tax->actions = '<div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.taxes', ['id' => $tax->id]) . '" class="">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($tax->estado == 1) {
                $tax->actions .= '<li class="list-dropdown-actions">
                                                <a onclick="procesar_actualizar(' . $tax->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                            </li>';
            } else {
                $tax->actions .= '<li class="list-dropdown-actions">
                                                <a onclick="procesar_actualizar(' . $tax->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                            </li>';
            }
            $tax->actions .= '</ul> </div> </div>';


            if ($tax->estado == 1) {
                $tax->estado = '<span class="label label-success">Activo</span>';
            } else {
                $tax->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
