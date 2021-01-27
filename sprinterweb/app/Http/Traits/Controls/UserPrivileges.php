<?php

namespace App\Http\Traits\Controls;

use App\Privileges\Entities\Privileges;
use Illuminate\Support\Facades\Auth;

trait UserPrivileges
{
    public function privilegios(){
        $privilegios = Privileges::data($this->privilegios, $this->user);
        return $privilegios;
    }
    public function privilegios_navbar_menu(){
        if ($this->user == 'ADMINISTRADOR'){
            $privilegios= array();
        }else{
            $privilegios = Privileges::data_menu('99.01', $this->user);
        }
        return $privilegios;
    }
}
