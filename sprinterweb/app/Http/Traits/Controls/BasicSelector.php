<?php

namespace App\Http\Traits\Controls;

trait BasicSelector
{
    public function selector_basico($modelo,$name)
    {
        $selector = "<select name = 'cbo_". $name ."' id = 'cbo_". $name ."' class='form-control select2 ag-modal-select'>";
        $selector .= "<option value='0'>-Seleccionar-</option>";
        foreach($modelo as $m){
            $selector .=  "<option value='". $m->id ."'>" . $m->codigo .' | '. $m->descripcion . "</option>";
        }
        $selector .= "</select>";

        return $selector;
    }
}
