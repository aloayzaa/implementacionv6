<?php

namespace App\MeasurementUnits\Collections;

class MeasurementCollection
{

    public function actions($measurementsUnits)
    {
        foreach ($measurementsUnits as $measurementsUnit) {

            if ($measurementsUnit->estado == 1) {
                $measurementsUnit->estado = '<span class="label label-success">Activo</span>';
            } elseif ($measurementsUnit->estado == 0) {
                $measurementsUnit->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($measurementsUnit->estado == 2) {
                $measurementsUnit->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }

}
