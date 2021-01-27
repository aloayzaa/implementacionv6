<?php

namespace App\Categories\Collections;

class CategoriesCollection
{
    public function actions($categories)
    {
        foreach ($categories as $category) {
            $category->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                <a href="' . route('edit.banksopening', ['banksopening' => $category->id]) . '">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>';
            if ($category->estado == 1) {
                $category->actions .= '<li class="list-dropdown-actions">
                                    <a  onclick="procesar_actualizar(' . $category->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $category->actions .= '<li class="list-dropdown-actions">
                                    <a onclick="procesar_actualizar(' . $category->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $category->actions .= '</ul> </div> </div>';

            if ($category->estado == 1) {
                $category->estado = '<span class="label label-success">Activo</span>';
            } else {
                $category->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
