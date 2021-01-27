<?php

namespace App\DocumentToPay\Collections;

/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 10/04/19
 * Time: 05:56 PM
 */

class ProvisionsToPayCollection
{
    public function actions($provisiostopay)
    {
        foreach ($provisiostopay as $ptp) {
            $ptp->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                        <li class="list-dropdown-actions">
                                                <a href="' . route('edit.provisionstopay', ['provisionstopay' => $ptp->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($ptp->estado == 1) {
                $ptp->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $ptp->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $ptp->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $ptp->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $ptp->actions .= '</ul> </div> </div>';


            if ($ptp->estado == 1) {
                $ptp->estado = '<span class="label label-success">Activo</span>';
            } else {
                $ptp->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
