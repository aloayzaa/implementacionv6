<?php

namespace App\Customers\Collections;

class CustomerCollection
{

    public function actions($customers)
    {
        foreach ($customers as $customer) {
            $customer->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.customers', ['customer' => $customer->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($customer->estado == 1) {
                $customer->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $customer->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $customer->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $customer->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $customer->actions .= '</ul> </div> </div>';

            if ($customer->estado == 1) {
                $customer->estado = '<span class="label label-success">Activo</span>';
            } else {
                $customer->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
