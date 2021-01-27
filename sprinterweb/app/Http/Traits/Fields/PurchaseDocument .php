<?php

namespace App\Http\Traits\Fields;

use App\DocumentToPay\Entities\DocumentToPay;
use App\Procedures\DocumentToPayProcedure;

trait PurchaseDocument // clasic: clases - campos - cntdoccompra
{
    public function t_sgtenumero($documento_id, $serie, $origen){ // cntdoccompra - método sgtenumero
        if ($origen == "C"){
            $docxpagar = DocumentToPay::ultimonumero($documento_id, $serie, $origen);
            $numero = (isset($docxpagar->numerodoc)) ? $docxpagar->numerodoc : 0;
            return $numero + 1;
        }
    }

    public function t_validafecha($tipo_doc, $serie, $numero, $fecha){ // cntdoccompra - método validafecha
        $documentotopayprocedure = new DocumentToPayProcedure();
        $validafecha = $documentotopayprocedure->validafecha($tipo_doc, $serie, $numero, $fecha, '@a'); // clase docxpagar - método validafecha
        return ($validafecha == '0') ? getMesaage(89) : '';
    }

    public function t_validadocumento($tercero_id, $documento_id, $serie, $numero, $id){ // cntdoccompra - método validadocumento
        $origen = "C"; // objeto: Cntdoccompra1, propiedad: origen 
        $validadoc = DocumentToPay::validadoc($documento_id, $serie, $numero, $origen);
        $docxpagar_id = 0;
        if ($validadoc) {
            if ($tercero_id == 0 || $tercero_id == null) {
                $docxpagar_id = (isset($validadoc->id)) ? $validadoc->id : 0;
            } else {
                if ($validadoc->controlnum == 1) {
                    $docxpagar_id = (isset($validadoc->id)) ? $validadoc->id : 0;            
                } else {
                    if ($validadoc->tercero_id == $tercero_id) {
                        $docxpagar_id = (isset($validadoc->id)) ? $validadoc->id : 0;  
                    }                    
                }
            }
        }
        
        return ($docxpagar_id > 0 && $docxpagar_id != $id) ? getMesaage(28) : null;
    }
}

