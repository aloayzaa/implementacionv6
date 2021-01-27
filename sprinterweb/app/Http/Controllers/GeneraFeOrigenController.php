<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GeneraFEController;

class GeneraFeOrigenController extends Controller
{
    private $origen;

    public function __construct(GeneraFEController $generafe)
    {


        $this->origen = $generafe->origen;


    }       
}
