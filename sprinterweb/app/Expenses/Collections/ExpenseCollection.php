<?php

namespace App\Expenses\Collections;

class ExpenseCollection
{

    public function actions($expenses)
    {
        foreach ($expenses as $expense) {

            $expense->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="' . route('edit.expenses', ['id' => $expense->id]) . '">Actualizar 
                                 <span class="fa fa-pencil text-primary"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>';

            if ($expense->estado == 1) {
                $expense->estado = '<span class="label label-success">Activo</span>';
            } elseif ($expense->estado == 0) {
                $expense->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($expense->estado == 2) {
                $expense->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
