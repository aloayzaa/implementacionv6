<?php

namespace App\Documents\Collections;

class IdentificationCollection
{

    public function actions($identifications)
    {
        foreach ($identifications as $identification) {

            if ($identification->estado == 1) {
                $identification->estado = '<span class="label label-success">Activo</span>';
            } elseif ($identification->estado == 0) {
                $identification->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($identification->estado == 2) {
                $identification->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
