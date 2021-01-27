<?php

namespace App\AccountingPlan\Collections;


class AccountingPlanCollection
{

    public function actions($accountingplans)
    {
        foreach ($accountingplans as $accountingplan) {

            if ($accountingplan->estado == 1) {
                $accountingplan->estado = '<span class="label label-success">Activo</span>';
            } elseif ($accountingplan->estado == 0) {
                $accountingplan->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($accountingplan->estado == 2) {
                $accountingplan->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }
}
