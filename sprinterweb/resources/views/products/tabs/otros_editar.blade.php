<div class="row">
    <div class="col-md-2 col-xs-12 label-input">
        <label for="txt_producto">Producto:</label>
    </div>
    <div class="col-md-3 col-xs-12">
        <input type="text" id="txt_codigo_vista" name="txt_codigo_vista" class="form-control" value="{{$product->codigo}}" readonly>
    </div>
    <div class="col-md-5 col-xs-12">
        <input type="text" id="txt_descripcion_vista" name="txt_descripcion_vista" class="form-control" value="{{$product->descripcion}}" readonly>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xs-12">
        <p class="title-view">Ubicaciones por Almacén:</p>
        <a id="modal_ubicacion_almacen" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
        <table class="table table-bordered" id="tabla_ubicacion_almacen">
            <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Ubicación</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        <br>
        <div class="row">
            <div class="col-md-2 col-xs-12 label-input">
                <label for="cbo_tipo_carga">Tipo de Carga:</label>
            </div>
            <div class="col-md-10 col-xs-12">
                <select name="cbo_tipo_carga" id="cbo_tipo_carga" class="form-control select2 ag-modal-select">
                    <option value="0">-Seleccionar-</option>
                    <option value="1" @if($product->tipocarga == 1) selected @endif>De muy alto riesgo</option>
                    <option value="2" @if($product->tipocarga == 2) selected @endif>Contaminado microbiológicamente</option>
                    <option value="3" @if($product->tipocarga == 3) selected @endif>Con un riesgo físco y/o químico</option>
                    <option value="4" @if($product->tipocarga == 4) selected @endif>Materiales neutros</option>
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-10 col-xs-12">
                <label for="txt_otros_especificos">Otros Especif:</label><br>
                <textarea  class="form-control" id="txt_otros_especificos" name="txt_otros_especificos"></textarea>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-10 col-xs-12">
                <label for="txt_partida_arancelaria">Partida Arancelaria:</label>
                <select class="form-control select2" name="cbo_partida_arancelaria" id="cbo_partida_arancelaria">
                    <option value="{{$product->parancelaria_id}}">{{$product->parancelaria['codigo']}} | {{$product->parancelaria['descripcion']}}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <p class="title-view">Información Agrícola:</p>
        <div class="row">
            <div class="col-md-3 col-xs-12 label-input">
                <label for="txt_ingred_activo">Ingred. Activo</label>
            </div>
            <div class="col-md-9 col-xs-12">
                <input type="text" name="txt_ingred_activo" id="txt_ingred_activo" class="form-control" value="{{$product->ingactivo}}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-xs-12 label-input">
                <label for="txt_concentracion_ia">Concentración I.A</label>
            </div>
            <div class="col-md-9 col-xs-12">
                <input type="text" name="txt_concentracion_ia" id="txt_concentracion_ia" class="form-control" value="{{$product->iac}}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-xs-12 label-input">
                <label for="txt_formulacion">Formulación</label>
            </div>
            <div class="col-md-9 col-xs-12">
                <input type="text" name="txt_formulacion" id="txt_formulacion" class="form-control" value="{{$product->formulacion}}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-xs-12 label-input">
                <label for="txt_metodo_accion">Método Acción</label>
            </div>
            <div class="col-md-9 col-xs-12">
                <input type="text" name="txt_metodo_accion" id="txt_metodo_accion" class="form-control" value="{{$product->metodoaccion}}">
            </div>
        </div>
        <div class="row">
            <p class="title-view">Datos NPK:</p>
            <a id="modal_datos_npk" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
            <table class="table table-bordered" id="tabla_datos_npk">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Conc. (%)</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
