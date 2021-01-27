<?php


namespace App\DocumentToPay\Collections;

class WithholdingDocumentsCollection
{
    public function reference($resultados)
    {
        foreach ($resultados as $resultado) {

            $resultado->elegido = '<input type="checkbox" name="chkAplica' . $resultado->id . '" id="chkAplica' . $resultado->id . '" onclick="aplica_ref(' . $resultado->id . ',' . $resultado->saldomn . ',' . $resultado->saldome . ')">';
            $resultado->aplicar = '<input type="text" name="txt_aplica' . $resultado->id . '" id="txt_aplica' . $resultado->id . '" value="0.00" class="form-control" readonly onchange="format(' . $resultado->id . ')">';
        }
    }
}
