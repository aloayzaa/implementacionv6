<?php
/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 15/04/19
 * Time: 03:54 PM
 */

namespace App\DocumentToPay\Collections;


class CreditDebitNotesCollection
{
    public function actions($creditdebitnotes, $route)
    {
        foreach ($creditdebitnotes as $cdn) {
            $cdn->select = '<div class="btn-group">
                                    <div class="btn-group">
                                       <input type="checkbox" id="referencia' . $cdn->id . '" onclick="lista_detalle_referencia(\'' . $route . '\', ' . $cdn->id . ')">
                                    </div>
                            </div>';
        }
    }

    public function actionsdetails($creditdebitnotesdetails)
    {
        foreach ($creditdebitnotesdetails as $cdnd) {
            $cdnd->select = '<div class="btn-group">
                                    <div class="btn-group">
                                       <input type="checkbox" checked readonly>
                                    </div>
                            </div>';

            $cdnd->aplicar = '<div class="btn-group">
                                    <div class="btn-group">
                                       <input type="text" value="' . $cdnd->importe . '" id="apply" name="apply">
                                    </div>
                            </div>';
        }
    }

    public function actionlist($creditdebitnotes)
    {
        foreach ($creditdebitnotes as $creditdebitnote) {
            $creditdebitnote->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.creditdebitnotes', ['creditdebitnote' => $creditdebitnote->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($creditdebitnote->estado == 1) {
                $creditdebitnote->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $creditdebitnote->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $creditdebitnote->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $creditdebitnote->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $creditdebitnote->actions .= '</ul> </div> </div>';

            if ($creditdebitnote->estado == 1) {
                $creditdebitnote->estado = '<span class="label label-success">Activo</span>';
            } else {
                $creditdebitnote->estado = '<span class="label label-danger">Inactivo</span>';
            }
        }
    }
}
