<?php

namespace App\Users\Collections;

use App\Companies\Entities\Empresa;

/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 11/02/19
 * Time: 06:22 PM
 */
class UserCollection
{
    public function companiesUser($id_empresa)
    {
        $companies = Empresa::select('e.id AS id', 'e.emp_razon_social AS name')
            ->from('empresa as e')
            ->where('e.emp_primaria_id', $id_empresa)
            ->get();

        return $companies;
    }
}
