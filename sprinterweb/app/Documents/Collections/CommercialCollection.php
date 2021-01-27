<?php

namespace App\Documents\Collections;

class CommercialCollection
{

    public function actions($commercials, $route)
    {
        foreach ($commercials as $commercial) {

            $commercial->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="'. route($route, ['id' => $commercial->id]) .'">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>';

            if ($commercial->estado == 1) {
                $commercial->estado = '<span class="label label-success">Activo</span>';
            } elseif ($commercial->estado == 0) {
                $commercial->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($commercial->estado == 2) {
                $commercial->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
