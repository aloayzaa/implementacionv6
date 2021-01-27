<?php


namespace App\DocumentToPay\Collections;


class OtherProvisionsCollection
{
    public function actions($documents)
    {
        foreach ($documents as $document) {
            $document->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a href="#" data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="' . asset('img/arrowDown.png') . '">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                        <li class="list-dropdown-actions">
                                                <a href="' . route('edit.otherprovisions', ['document' => $document->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($document->estado == 1) {
                $document->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $document->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $document->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $document->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $document->actions .= '</ul> </div> </div>';


            if ($document->estado == 1) {
                $document->estado = '<span class="label label-success">Activo</span>';
            } else {
                $document->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
