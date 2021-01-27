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
                    <option value="{{ $family->id }}" data-codigo="{{ $family->codigo }}" @if($product->familiapdto_id == $family->id) selected @endif>
                        {{ $family->codigo }} | {{ $family->descripcion }}
                    </option>
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
            <input type="text" id="txt_codigo_producto" name="txt_codigo_producto" class="form-control" value="{{$product->codigo}}">
        </div>
        <div class="col-md-8 col-xs-12">
            <input class="form-control" type="text" name="txt_descripcion_producto" id="txt_descripcion_producto" value="{{$product->descripcion}}">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-xs-12" style="line-height: 25px;">
            <label>Código Sunat:</label>
        </div>
        <div class="col-md-10 col-xs-12">
            <select class="select2" name="sunat" id="sunat">
                <option value="{{$product->productosunat_id}}" selected>{{$product->producto_sunat['codigo']}} | {{$product->producto_sunat['descripcion']}}</option>
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
                       @if($product->tipoproducto == 'M')
                       checked @endif>
                <label for="greensea1">Existencia</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="2"
                       id="greensea2"
                       name="opt_tipoproducto"
                       @if($product->tipoproducto == 'S')
                       checked @endif>
                <label for="greensea2">Servicio/Gasto</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="3"
                       id="greensea3"
                       name="opt_tipoproducto"
                       @if($product->tipoproducto == 'A')
                       checked @endif>
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
                       @if($product->tipomercaderia == 'M')
                       checked @endif>
                <label for="op1">Mercadería/Mat. Prima</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="2"
                       id="op2"
                       name="opt_mercaderia"
                       @if($product->tipomercaderia == 'I')
                       checked @endif>
                <label for="op2">Semi-Terminado</label>
            </div>
            <div class="radio icheck-peterriver radio-inline">
                <input type="radio"
                       value="3"
                       id="op3"
                       name="opt_mercaderia"
                       @if($product->tipomercaderia == 'T')
                       checked @endif>
                <label for="op3">Producto Terminado</label>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 col-xs-12" style="margin-top:-5px;">
    <div class="row">
        <div class="col-md-2 col-xs-2"></div>
        <div class="col-md-10 col-xs-10">
            <div class="col-md-6 col-sm-12 col-xs-12">
                @if($product->archivo_foto != '')
                    <img src="{{asset('images/'.$product->archivo_foto)}}" style="width: 100px;height: 100px;" class="img-responsive">
                    <input type="hidden" name="imagen_antigua" id="imagen_antigua" value="{{$product->archivo_foto}}">
                @endif
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="wrap-custom-file">
                    <input type="file" src="{{asset('images/'.$product->archivo_foto)}}"
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
                    <select class="select2" name="compra" id="compra" disabled>
                        <option value="{{$product->ucompra_id}}">{{$product->unidadmedidacompra['codigo']}} | {{$product->unidadmedidacompra['descripcion']}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Kardex:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="kardex" id="kardex" disabled>
                        <option value="{{$product->umedida_id}}" selected>{{$product->unidadmedida['codigo']}} | {{$product->unidadmedida['descripcion']}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Conversión:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <input type="number" class="form-control" value="{{$product->conversion}}" name="conversion"
                           id="conversion" disabled> 1 UM Compra = ? UM
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
                    <input type="text" class="form-control" name="name" id="name" value="{{$product->nombrecomercial}}">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Marca:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="mark" id="mark">
                        <option value="{{$product->marca_id}}">{{$product->tabla_marca['codigo']}} | {{$product->tabla_marca['descripcion']}}</option>
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
                        <option value="{{$product->modelo_id}}" selected>{{$product->tabla_modelo['codigo']}} | {{$product->tabla_modelo['descripcion']}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="txt_grupo">Grupo:</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="txt_grupo"  id="txt_grupo">
                        <option value="{{$product->grupoproducto_id}}">{{$product->grupo_producto['codigo']}} | {{$product->grupo_producto['descripcion']}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="name">Color</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <select class="select2" name="color" id="color">
                        <option value="{{$product->color_id}}">{{$product->color['codigo']}} | {{$product->color['descripcion']}}</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <label for="otros">Otros</label>
                </div>
                <div class="col-md-6 col-xs-12">
                    <textarea class="form-control" name="otros" id="otros">{{$product->caracteristicas}}</textarea>
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
                            <input type="text" class="form-control" name="ean" id="ean" value="{{$product->ean}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="cumseps">CUM-SEPS:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="cumseps" id="cumseps" value="{{$product->cumseps}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="peso">Peso (Kg):</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="peso" id="peso" value="{{$product->peso}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="volumen">Volumen (m3):</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="volumen" id="volumen" value="{{$product->volumen}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="longitud">Longitud (mt):</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="longitud"
                                   id="longitud" value="{{$product->longitud}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="talla">Talla:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="talla" id="talla" value="{{$product->talla}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="sminimo">Stock Mínimo:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="sminimo" id="sminimo" value="{{$product->stkminimo}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="smaximo">Stock Máximo:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="smaximo" id="smaximo" value="{{$product->stkmaximo}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="utilidad">% Utilidad:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="utilidad"
                                   id="utilidad" value="{{$product->utilidad}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label for="toleracion">% Toleracion:</label>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" name="toleracion"
                                   id="toleracion" value="{{$product->tolerancia}}">
                        </div>
                    </div>
                    <br>
                </div>
                <div class="col-md-5 col-sm-4 col-xs12">
                    <br><br><br>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input class="form-control" type="checkbox" id="chklote" name="chklote" @if($product->pidelote == 1)
                                checked @endif>
                                <label for="chklote">Pide Lote</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox" id="chkserie" name="chkserie" @if($product->pideserie == 1)
                                checked @endif>
                                <label for="chkserie">Pide Serie</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkcompra"
                                       name="chkcompra" @if($product->compra == 1)
                                       checked @endif>
                                <label for="chkcompra">Se Compra</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkvende"
                                       name="chkvende" @if($product->venta == 1)
                                       checked @endif>
                                <label for="chkvende">Se Vende</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkaumento"
                                       name="chkaumento" @if($product->aumentovta == 1)
                                       checked @endif>
                                <label for="chkaumento">Venta con aumento</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkmerma"
                                       name="chkmerma" @if($product->merma == 1)
                                       checked @endif>
                                <label for="chkmerma">Es Merma/Descarte</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-xs-12">
                            <div class="radio icheck-peterriver radio-inline">
                                <input type="checkbox"
                                       id="chkbonifica"
                                       name="chkbonifica" @if($product->bonificado == 1)
                                       checked @endif>
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
                                   @if($product->esafecto == 1)
                                   checked @endif>
                            <label for="chkafecto">Impuestos a las ventas</label>
                        </div>
                        <div class="icheck-info">
                            <input type="checkbox"
                                   id="chkpercepcion"
                                   name="chkpercepcion" @if($product->percepcion == 1)
                                   checked @endif>
                            <label for="chkpercepcion">Impuesto de percepción</label>
                        </div>
                        <div class="icheck-primary">
                            <input type="checkbox"
                                   id="chkconsumo"
                                   name="chkconsumo" @if($product->consumo == 1)
                                   checked @endif>
                            <label for="chkconsumo">Recargo al consumo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
