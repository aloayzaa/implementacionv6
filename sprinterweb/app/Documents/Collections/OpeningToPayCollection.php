<?php

namespace App\Documents\Collections;

class OpeningToPayCollection
{

    public function actions($openings)
    {
        foreach ($openings as $opening) {

            $opening->document = $opening->numero . ' ' . $opening->seriedoc . '-' . $opening->numerodoc;
            $opening->fechaproceso = date("d-m-Y", strtotime($opening->fechaproceso));;

            $opening->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="' . route( 'show.openingToPay', ['id' => $opening->id]) . '">Ver <span class="fa fa-eye text-success"></span></a>
                            </li>
                            <li class="list-dropdown-actions">
                                 <a href="' . route( 'edit.openingToPay', ['id' => $opening->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>';
            if ($opening->estado == 1) {
                $opening->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $opening->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                    </li>';
            } else {
                $opening->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $opening->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                    </li>';
            }
            $opening->actions .= '</ul>
                            </div>
                        </div>';


            if ($opening->estado == 1) {
                $opening->estado = '<span class="label label-success">Activo</span>';
            } elseif ($opening->estado == 0) {
                $opening->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($opening->estado == 2) {
                $opening->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
