<?php

namespace App\Subsidiaries\Collections;


class SubsidiaryCollection
{

    public function actions($subsidiaries)
    {
        foreach ($subsidiaries as $subsidiary) {
            $subsidiary->actions = '<div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.subsidiaries', ['id' => $subsidiary->id]) . '" class="">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($subsidiary->estado == 1) {
                $subsidiary->actions .='<li class="list-dropdown-actions">
                                                <a onclick="procesar_actualizar(' . $subsidiary->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                            </li>';
            } else {
                $subsidiary->actions .='<li class="list-dropdown-actions">
                                                <a onclick="procesar_actualizar(' . $subsidiary->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                            </li>';
            }
            $subsidiary->actions .= '</ul> </div> </div>';


            if ($subsidiary->estado == 1) {
                $subsidiary->estado = '<span class="label label-success">Activo</span>';
            } else {
                $subsidiary->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
