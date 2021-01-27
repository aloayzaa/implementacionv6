<?php

namespace App\Marks\Collections;


class MarcaCollection
{

    public function actions($marks)
    {
        foreach ($marks as $mark) {
            $mark->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.trademarks', ['mark' => $mark->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($mark->estado == 1) {
                $mark->actions .= '<li class="list-dropdown-actions">
                                    <a  onclick="procesar_actualizar(' . $mark->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $mark->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $mark->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $mark->actions .= '</ul> </div> </div>';

            if ($mark->estado == 1) {
                $mark->estado = '<span class="label label-success">Activo</span>';
            } else {
                $mark->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
