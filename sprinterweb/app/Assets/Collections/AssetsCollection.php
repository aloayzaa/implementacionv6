<?php

namespace App\Assets\Collections;


class AssetsCollection
{
    public function actions($assets)
    {
        foreach ($assets as $active) {
            $active->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.banksopening', ['banksopening' => $active->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($active->estado == 1) {
                $active->actions .= '<li class="list-dropdown-actions">
                                    <a  onclick="procesar_actualizar(' . $active->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $active->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $active->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $active->actions .= '</ul> </div> </div>';

            if ($active->estado == 1) {
                $active->estado = '<span class="label label-success">Activo</span>';
            } else {
                $active->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
