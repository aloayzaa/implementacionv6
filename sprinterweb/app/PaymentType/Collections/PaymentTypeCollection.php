<?php


namespace App\PaymentType\Collections;


class PaymentTypeCollection
{
    public function actions($operations)
    {
        foreach ($operations as $operation) {
            if ($operation->estado == 2) {
                $operation->estado = '<span class="label label-default">Administrador</span>';
            } else {
                $operation->estado = '<span class="label label-danger">Inactivo</span>';
            }
        }
    }

}
