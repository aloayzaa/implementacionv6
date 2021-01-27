<?php

namespace App\SalesTypes\Collections;

class TypeSaleCollection
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
                            <li>
                                 <a href="'. route($route, ['id' => $saleType->id]) .'">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>';
            if ($saleType->estado == 1) {
                $saleType->actions .= '<li>
                                        <a onclick="procesar_actualizar(' . $saleType->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                    </li>';
            } else if ($saleType->estado == 0) {
                $saleType->actions .= '<li>
                                        <a onclick="procesar_actualizar(' . $saleType->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                    </li>';
            }
            $saleType->actions .= '</ul>
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
