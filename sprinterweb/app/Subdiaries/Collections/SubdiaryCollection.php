<?php

namespace App\Subdiary\Collections;


class SubdiaryCollection
{

    public function actions($subdiaries)
    {
        foreach ($subdiaries as $subdiary) {

            if ($subdiary->estado == 1) {
                $subdiary->estado = '<span class="label label-success">Activo</span>';
            } elseif ($subdiary->estado == 0) {
                $subdiary->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($subdiary->estado == 2) {
                $subdiary->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
