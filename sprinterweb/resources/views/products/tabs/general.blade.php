<div class="col-md-8 col-xs-12">
    <div class="row">
        <div class="col-md-2 col-xs-12 label-input">
            <label for="family">Familia:</label>
        </div>
        <div class="col-md-10 col-xs-12">
            <select class="form-control select2"
                    name="cbo_familia"
                    id="cbo_familia">
                <option selected value="0">-Seleccione-</option>
                @foreach($families  as $family)
                    @if(strlen($family->codigo) == 3)
                        <option value="{{ $family->id }}" data-codigo="{{ $family->codigo }}">{{ $family->codigo }} | {{ $family->descripcion }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-xs-12" style="line-height: 25px;">
            <label>Código / Nombre:</label>
        </div>
        <div class="col-md-2 col-xs-12">
            <input type="text" id="txt_codigo_producto" name="txt_codigo_producto" class="form-control">
        </div>
        <div class="col-md-8 col-xs-12">
            <input class="form-control" type="text" name="txt_descripcion_producto" id="txt_descripcion_producto" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-xs-12" style="line-height: 25px;">
            <label>Código Sunat:</label>
        </div>
        <div class="col-md-10 col-xs-12">
            <select class="select2" name="sunat" id="sunat">
                <option value="">-Seleccionar-</option>
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-xs-12" style="line-height: 45px;">
            <label>Tipo de producto:</label>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="1"
                       id="greensea1"
                       name="opt_tipoproducto"
                       checked>
                <label for="greensea1">Existencia</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="2"
                       id="greensea2"
                       name="opt_tipoproducto">
                <label for="greensea2">Servicio/Gasto</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="3"
                       id="greensea3"
                       name="opt_tipoproducto">
                <label for="greensea3">Activo Fijo</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 col-xs-12" style="line-height: 45px;">
            <label>Tipo Existencia:</label>
        </div>
        <div class="col-md-9 col-xs-12">
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="1"
                       id="op1"
                       name="opt_mercaderia"
                       checked>
                <label for="op1">Mercadería/Mat. Prima</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="2"
                       id="op2"
                       name="opt_mercaderia">
                <label for="op2">Semi-Terminado</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="3"
                       id="op3"
                       name="opt_mercaderia">
                <label for="op3">Producto Terminado</label>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 col-xs-12" style="margin-top:-5px;">
    <div class="row">
        <div class="col-md-2 col-xs-2"></div>
        <div class="col-md-10 col-xs-10">
            <div class="wrap-custom-file">
                <input type="file"
                       name="imagen"
                       id="image1"
                       accept=".gif, .jpg, .png"/>
                <label for="image1">
                    <span>Adjuntar imagen</span>
                    <i class="fa fa-plus-circle"></i>
                </label>
            </div>
        </div>
    </div>
</div>
<br>
<div class="col-md-6 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <p class="title-view">Unidades de Medida:</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="compra">Compra:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="compra" id="compra">
                        <option value="">-Seleccionar-</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Kardex:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="kardex" id="kardex">
                        <option value="">-Seleccionar-</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Conversión:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <input type="number" class="form-control" value="1.000" name="conversion"
                           id="conversion"> 1 UM Compra = ? UM
                    Kardex
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <p class="title-view">Descripción Adicional:</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Nom. Comercial:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Marca:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="mark" id="mark">
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Modelo:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="form-control select2" name="model" id="model">
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="txt_grupo">Grupo:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="txt_grupo"  id="txt_grupo"></select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Color</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="color" id="color"></select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="otros">Otros</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <textarea class="form-control" name="otros" id="otros"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-7 col-sm-8 col-xs-12">
                    <div class="row">
                        <p class="title-view">Otras características:</p>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="ean">UPC/EAN:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="ean" id="ean">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="cumseps">CUM-SEPS:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="cumseps" id="cumseps">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="peso">Peso (Kg):</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="peso" id="peso">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="volumen">Volumen (m3):</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="volumen" id="volumen">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="longitud">Longitud (mt):</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="longitud"
                                   id="longitud">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="talla">Talla:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="talla" id="talla">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="sminimo">Stock Mínimo:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="sminimo" id="sminimo">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="smaximo">Stock Máximo:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="smaximo" id="smaximo">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="utilidad">% Utilidad:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="utilidad"
                                   id="utilidad">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="toleracion">% Toleracion:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="toleracion"
                                   id="toleracion">
                        </div>
                    </div>
                    <br>
                </div>
                <div class="col-md-5 col-sm-4 col-xs12">
                    <br><br><br>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input class="form-control" type="checkbox" id="chklote" name="chklote">
                                <label for="chklote">Pide Lote</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox" id="chkserie" name="chkserie">
                                <label for="chkserie">Pide Serie</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkcompra"
                                       name="chkcompra">
                                <label for="chkcompra">Se Compra</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkvende"
                                       name="chkvende">
                                <label for="chkvende">Se Vende</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkaumento"
                                       name="chkaumento">
                                <label for="chkaumento">Venta con aumento</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkmerma"
                                       name="chkmerma">
                                <label for="chkmerma">Es Merma/Descarte</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkbonifica"
                                       name="chkbonifica">
                                <label for="chkbonifica">Es Bonificado</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <p class="title-view">Afectación Fiscal / Otros:</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="col-md-12 col-xs-12">
                        <div class="icheck-peterriver">
                            <input type="checkbox"
                                   id="chkafecto"
                                   name="chkafecto"
                                   checked>
                            <label for="chkafecto">Impuestos a las ventas</label>
                        </div>
                        <div class="icheck-info">
                            <input type="checkbox"
                                   id="chkpercepcion"
                                   name="chkpercepcion">
                            <label for="chkpercepcion">Impuesto de percepción</label>
                        </div>
                        <div class="icheck-primary">
                            <input type="checkbox"
                                   id="chkconsumo"
                                   name="chkconsumo">
                            <label for="chkconsumo">Recargo al consumo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
