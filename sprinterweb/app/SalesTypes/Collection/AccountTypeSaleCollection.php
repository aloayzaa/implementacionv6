<?php

namespace App\SalesTypes\Collections;

class AccountTypeSaleCollection
{

    public function actions($salesTypes, $route)
    {
        foreach ($salesTypes as $saleType) {

            $saleType->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="'. route($route, ['id' => $saleType->id]) .'">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>';

            if ($saleType->estado == 1) {
                $saleType->estado = '<span class="label label-success">Activo</span>';
            } elseif ($saleType->estado == 0) {
                $saleType->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($saleType->estado == 2) {
                $saleType->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }

}
