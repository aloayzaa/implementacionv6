<?php

namespace App\Panel\Accounting\Collections;

/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 11/02/19
 * Time: 06:22 PM
 */
class AccountingCollection
{
    public function actions($datas)
    {
        foreach ($datas as $data) {
            $data->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">';
            if ($data->emp_estado == 1) {
                $data->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $data->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $data->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $data->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $data->actions .= '</ul> </div> </div>';


            if ($data->emp_estado == 1) {
                $data->emp_estado = '<span class="label label-success">Activo</span>';
            } else {
                $data->emp_estado = '<span class="label label-danger">Desactivado</span>';
            }

            $data->empresas = '<a href="' . route('index.companies', ['empresa_id' => $data->id]) . '"
                        data-toggle="tooltip" data-placement="left" title="Empresas" class="icon-button pointSale" id="btnPoint">
                        <i class="fa fa-building"></i><span></span></a>';

            $data->usuarios = '<a href="' . route('index.users', ['empresa_id' => $data->id]) . '"
                        data-toggle="tooltip" data-placement="left" title="Usuarios" class="icon-button pointSale" id="btnPoint">
                        <i class="fa fa-user"></i><span></span></a>';

            $data->suscripcion = '<a href="' . route('index.suscriptions', ['empresa_id' => $data->id]) . '"
                        data-toggle="tooltip" data-placement="left" title="Suscripciones" class="icon-button pointSale" id="btnPoint">
                        <i class="fa fa-check"></i><span></span></a>';
        }
    }
}
