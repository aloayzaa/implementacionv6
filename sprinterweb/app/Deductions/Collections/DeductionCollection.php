<?php

namespace App\Deductions\Collections;

class DeductionCollection
{

    public function actions($deductions)
    {
        foreach ($deductions as $deduction) {

    $deduction->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                                            <img src="'. asset('img/arrowDown.png') .'">
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li class="list-dropdown-actions">
                                                 <a href="'. route('edit.deductions', ['id' => $deduction->id]) .'">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                            </div>';

            if ($deduction->estado == 1) {
                $deduction->estado = '<span class="label label-success">Activo</span>';
            } elseif ($deduction->estado == 0) {
                $deduction->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($deduction->estado == 2) {
                $deduction->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
