<?php

namespace App\Documents\Collections;

class AccountDocumentSaleCollection
{

    public function actions($accountsDocumentsSales, $route)
    {
        foreach ($accountsDocumentsSales as $accountDocumentSale) {

            $accountDocumentSale->actions =
                '<div class="btn-group">
                    <div class="btn-group">
                        <a data-toggle="dropdown" class="dropdown-toggle" > 
                            <img src="'. asset('img/arrowDown.png') .'">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="list-dropdown-actions">
                                 <a href="'. route($route, ['id' => $accountDocumentSale->id]) .'">Actualizar <span class="fa fa-pencil text-primary"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>';

            if ($accountDocumentSale->estado == 1) {
                $accountDocumentSale->estado = '<span class="label label-success">Activo</span>';
            } elseif ($accountDocumentSale->estado == 0) {
                $accountDocumentSale->estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($accountDocumentSale->estado == 2) {
                $accountDocumentSale->estado = '<span class="label label-primary">Sistema</span>';
            }
        }
    }

}
