<?php

namespace App\Bank\Collections;

class BanksOpeningCollection
{
    public function actions($bancos)
    {
        foreach ($bancos as $banco) {
            $banco->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.banksopening', ['banksopening' => $banco->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($banco->estado == 1) {
                $banco->actions .= '<li class="list-dropdown-actions">
                                    <a  onclick="procesar_actualizar(' . $banco->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $banco->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $banco->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $banco->actions .= '</ul> </div> </div>';

            if ($banco->estado == 1) {
                $banco->estado = '<span class="label label-success">Activo</span>';
            } else {
                $banco->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
