<?php

namespace App\ServiceOrders\Collection;

class ServiceOrdersCollection
{
    public function actions($datas)
    {
        foreach ($datas as $data) {
            $data->sel = '<button type="button" class="aplicar" name="chk' . $data->id . '" id="chk' . $data->id . '" onclick="aplica_ref(' . $data->id . ', ' . $data->nromanual . ', ' . $data->centrocosto_id . ')"><i class="fa fa-plus"></i></button>';
        }
    }
}
