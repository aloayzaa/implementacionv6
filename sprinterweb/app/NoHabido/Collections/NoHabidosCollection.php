<?php


namespace App\NoHabido\Collections;


class NoHabidosCollection
{
    public function actions($datas)
    {
        foreach ($datas as $data) {

            if ($data->estado == 1) {
                $data->estado = '<span class="label label-success">Activo</span>';
            } elseif ($data->estado == 2) {
                $data->estado = '<span class="label label-info">SISTEMA</span>';
            } else {
                $data->estado = '<span class="label label-danger">Desactivado</span>';
            }
        }
    }
}
