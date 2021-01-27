<?php

namespace App\Http\Controllers;

use App\Costs\Entities\CostByFamily;
use App\Costs\Entities\Costs;
use App\Http\Requests\MaestroRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class CostsByFamilyController extends Controller
{
    protected $var = '';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function index(){
        $ccosto_familia = CostByFamily::all();

    }


}
