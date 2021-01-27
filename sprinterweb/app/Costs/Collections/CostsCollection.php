<?php

namespace App\Costs\Collections;

class CostsCollection
{

    public function actions($costs)
    {
        foreach ($costs as $cost) {

            if ($cost->estado == 1) {
                $cost->estado = '<span class="label label-success">Activo</span>';
            } elseif ($cost->estado == 0) {
                $cost->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($cost->estado == 2) {
                $cost->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
