<?php

namespace App\AccountingNote\Collections;

class AccountingNoteCollection
{
    public function actions($seats, $variable)
    {
        foreach ($seats as $seat) {
            $seat->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.' . $variable, ['seat' => $seat->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($seat->estado == 1) {
                $seat->actions .= '<li class="list-dropdown-actions">
                                    <a  onclick="procesar_actualizar(' . $seat->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $seat->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $seat->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $seat->actions .= '</ul> </div> </div>';

            if ($seat->estado == 1) {
                $seat->estado = '<span class="label label-primary">Activo</span>';
            } elseif ($seat->estado == 0) {
                $seat->estado = '<span class="label label-danger">Inactivo</span>';
            }
        }
    }

    public
    function actionsreference($referencias)
    {
        foreach ($referencias as $referencia) {
            $referencia->checkbox = '<input type="checkbox" id="chkItemRef' . $referencia->id . '" name="chkItemRef[]">';

            $referencia->aplicar = '<input type="checkbox" id="chkItemRef' . $referencia->id . '" name="chkItemRef[]">';

            $referencia->documento = '<input type="checkbox" id="chkItemRef' . $referencia->id . '" name="chkItemRef[]">';

        }
    }

}
