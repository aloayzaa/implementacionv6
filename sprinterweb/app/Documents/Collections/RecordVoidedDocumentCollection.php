<?php

namespace App\Documents\Collections;

class RecordVoidedDocumentCollection
{

    public function actions($records)
    {
        foreach ($records as $record) {

            $record->document = $record->numero . ' ' . $record->seriedoc . '-' . $record->numerodoc;
            $record->fechaproceso = date("d-m-Y", strtotime($record->fechaproceso));;

            $record->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">';
            if ($record->estado == 1) {
                $record->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $record->id . ', 0)"  class="text-lg-center">Anular <span class="fa fa-close text-danger"></span></a>
                                    </li>';
            } else {
                $record->actions .= '<li class="list-dropdown-actions">
                                        <a onclick="procesar_actualizar(' . $record->id . ', 1 )">Activar <span class="fa fa-check text-success"></span></a>
                                    </li>';
            }
            $record->actions .= '</ul>
                            </div>
                        </div>';


            if ($record->estado == 1) {
                $record->estado = '<span class="label label-success">Activo</span>';
            } elseif ($record->estado == 0) {
                $record->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($record->estado == 2) {
                $record->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }

}


/*
 * <li>
                                 <a href="' . route( 'show.recordVoidedDocument', ['id' => $record->id]) . '">Ver <span class="fa fa-eye text-success"></span></a>
                            </li>
                            <li>
                                 <a href="' . route( 'edit.recordVoidedDocument', ['id' => $record->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>*/
