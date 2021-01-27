<?php

namespace App\DocumentToPay\Collections;

class BillingCollection
{

    public function actions($billings, $route)
    {
        foreach ($billings as $billing) {

            $billing->document = $billing->numero . ' ' . $billing->seriedoc . '-' . $billing->numerodoc;
            $billing->fechaproceso = date("d-m-Y", strtotime($billing->fechaproceso));;

            $billing->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="' . route($route, ['id' => $billing->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>';
            if ($billing->estado == 1) {
                $billing->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $billing->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                    </li>';
            } else {
                $billing->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $billing->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                    </li>';
            }
            $billing->actions .= '</ul>
                            </div>
                        </div>';


            if ($billing->estado == 1) {
                $billing->estado = '<span class="label label-success">Activo</span>';
            } elseif ($billing->estado == 0) {
                $billing->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($billing->estado == 2) {
                $billing->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }

}
