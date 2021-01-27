<?php

namespace App\Panel\Companies\Collections;

use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 11/02/19
 * Time: 06:22 PM
 */
class CompanyCollection
{
    public function actions($companies)
    {
        foreach ($companies as $company) {
            $company->sprinter = '<a href="' . route('conexion.panel', ['id_empresa' => $company->id]) . '"
                                    data-toggle="tooltip" data-placement="left" title="Sprinter" id="btnPoint">
                                    <img src="' . asset('img/pointSale.png') . '">
                                    </a>';
        }
    }
}
