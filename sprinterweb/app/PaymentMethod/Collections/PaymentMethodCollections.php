<?php

namespace App\PaymentMethod\Collections;

class PaymentMethodCollections
{

    public function actions($paymentMethods, $route)
    {
        foreach ($paymentMethods as $paymentMethod) {

            $paymentMethod->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="'. route($route, ['id' => $paymentMethod->id]) .'">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>';
            if ($paymentMethod->estado == 1) {
                $paymentMethod->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $paymentMethod->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                    </li>';
            } else {
                $paymentMethod->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $paymentMethod->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                    </li>';
            }
            $paymentMethod->actions .= '</ul>
                            </div>
                        </div>';

            if ($paymentMethod->estado == 1) {
                $paymentMethod->estado = '<span class="label label-success">Activo</span>';
            } elseif ($paymentMethod->estado == 0) {
                $paymentMethod->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($paymentMethod->estado == 2) {
                $paymentMethod->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }

}
