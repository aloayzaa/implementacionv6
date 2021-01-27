<?php

namespace App\Documents\Collections;


class TransitBankDocumentCollection
{
    public function actions($banks)
    {
        foreach ($banks as $bank) {
            $bank->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"> 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                        <li class="list-dropdown-actions">
                                                <a href="' . route('edit.transfers', ['transfers' => $bank->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($bank->estado == 1) {
                $bank->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $bank->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $bank->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $bank->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $bank->actions .= '</ul> </div> </div>';

            if ($bank->estado == 1) {
                $bank->estado = '<span class="label label-success">Activo</span>';
            } else {
                $bank->estado = '<span class="label label-danger">Inactivo</span>';
            }
        }
    }

}
