<?php

namespace App\Http\Controllers;

use App\AccountingTax\Entities\AccountingTax;
use App\Customers\Entities\Customer;
use App\AccountingPlan\Entities\AccountingPlan;
use App\PensionType\Entities\PensionType;
use App\Period\Entities\Period;
use App\PurchaseDocument\Entities\PurchaseDocument;
use App\Taxes\Collections\TaxesCollection;
use App\Taxes\Entities\Taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class TaxesController extends Controller
{
    private $var = 'taxes';
    private $taxescollection;

    public function __construct(TaxesCollection $taxesCollection)
    {
        $this->obtener_cliente();
        $this->taxescollection = $taxesCollection;
    }

    public function list()
    {
        $taxes = Taxes::all();
        $this->taxescollection->actions($taxes);
        return Datatables::of($taxes)->rawColumns(['estado', 'actions'])->make();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Impuesto', '');

        return view('taxes.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['route'] = route('taxes');
        $data['pensiones'] = PensionType::all();
        $data['documentocompras'] = PurchaseDocument::all();
        $data['terceros'] = Customer::all();
        $data['pcgs'] = AccountingPlan::all();
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Impuesto', '');

        return view('taxes.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $impuesto = new Taxes();
            $impuesto->codigo = $request->code;
            $impuesto->descripcion = $request->description;
            $impuesto->nombrecorto = $request->name;
            $impuesto->tipocalculo = $request->type;
            $impuesto->valor = $request->value;
            $impuesto->estado = 1;
            $impuesto->restabase = validaCheck($request->checkbase);
            $impuesto->vigentedesde = $request->initialdate;
            $impuesto->vigentehasta = $request->finaldate;
            $impuesto->iva = validaCheck($request->checkigv);
            $impuesto->retencion = validaCheck($request->checkretention);
            $impuesto->baseretencion = $request->retentionbase;
            $impuesto->calculoret = $request->calculationbase;
            $impuesto->usuario = Auth::id();
            $impuesto->documento_id = $request->retentiontype;
            $impuesto->tercero_id = $request->retentionname;
            $impuesto->tiporegpension_id = $request->pension;

            DB::transaction(function () use ($impuesto, $request) {
                $impuesto->save();
                if ($request->cuentamn != 0 || $request->cuentame != 0) {
                    $this->inserta_cuenta_mn_me_impuesto_contab($request, $impuesto->id);
                }
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Datos no válidos";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    private function inserta_cuenta_mn_me_impuesto_contab($request, $parent_id)
    {
        $impuesto_contab = new AccountingTax();
        $impuesto_contab->parent_id = $parent_id;
        $impuesto_contab->item = 0;
        $impuesto_contab->cuentamn_id = $request->cuentamn;
        $impuesto_contab->cuentame_id = $request->cuentame;
        $impuesto_contab->usuario = Auth::id();

        DB::transaction(function () use ($impuesto_contab) {
            $impuesto_contab->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['route'] = route('taxes');
        $data['pensiones'] = PensionType::all();
        $data['documentocompras'] = PurchaseDocument::all();
        $data['terceros'] = Customer::all();
        $data['pcgs'] = AccountingPlan::all();
        $data['impuesto'] = Taxes::findOrFail($id);
        $data['impuesto_contab'] = AccountingTax::where('parent_id', $id)->first();
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Impuesto', '');

        return view('taxes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $impuesto = Taxes::findOrFail($id);
            $impuesto->codigo = $request->code;
            $impuesto->descripcion = $request->description;
            $impuesto->nombrecorto = $request->name;
            $impuesto->tipocalculo = $request->type;
            $impuesto->valor = $request->value;
            $impuesto->restabase = validaCheck($request->checkbase);
            $impuesto->vigentedesde = $request->initialdate;
            $impuesto->vigentehasta = $request->finaldate;
            $impuesto->iva = validaCheck($request->checkigv);
            $impuesto->retencion = validaCheck($request->checkretention);
            $impuesto->baseretencion = $request->retentionbase;
            $impuesto->calculoret = $request->calculationbase;
            $impuesto->usuario = Auth::id();
            $impuesto->documento_id = $request->retentiontype;
            $impuesto->tercero_id = $request->retentionname;
            $impuesto->tiporegpension_id = $request->pension;

            DB::transaction(function () use ($impuesto, $request) {
                $impuesto->save();
                if ($request->cuentamn != 0 || $request->cuentame != 0) {
                    $this->editar_cuenta_mn_me_impuesto_contab($request, $impuesto->id);
                }
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Datos no válidos";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    private function editar_cuenta_mn_me_impuesto_contab($request, $parent_id)
    {
        $item = 0;
        $data = array(
            'cuentamn_id' => $request->cuentamn,
            'cuentame_id' => $request->cuentame,
            'usuario' => Auth::id()
        );

        AccountingTax::editarRefactorizado($parent_id, $item, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activate(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Taxes::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function calcularigv(Request $request)
    {
        if (!$request->igv) {
            $igv = 3;
        } else {
            $igv = $request->igv;
        }
        $resultado = Taxes::findOrFail($igv);
        $r['resultado'] = ($resultado->valor / 100) * $request->base;
        return $r;
    }

    public function calcularconigv(Request $request)
    {
        if (!$request->igv) {
            $igv = 3;
        } else {
            $igv = $request->igv;
        }
        $resultado = Taxes::findOrFail($igv);
        $r['resultado'] = $request->base / (1 + $resultado->valor);
        $r['total'] = $request->base - $r['resultado'];
        return $r;
    }

    public function calcularrent(Request $request)
    {
        $resultado = Taxes::findOrFail($request->rent);
        if ($request->base == '0.00') {
            $valor = $request->inactive;
        } elseif ($request->inactive == '0.00') {
            $valor = $request->base;
        }
        $r['resultado'] = ($resultado->valor / 100) * $valor;

        return $r;
    }
}
