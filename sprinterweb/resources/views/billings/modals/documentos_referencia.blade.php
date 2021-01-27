<div class="modal fade" id="modalDocumentosReferencia" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="color: black"><span class="fa fa-check"></span> Documentos de Referencia</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-documentos-referencia" name="form-documentos-referencia">
                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <label>Tipo:</label><br>
                            <select name="referencia_tipo" id="referencia_tipo" class="select2">
                                <option value="1">Orden trabajo</option>
                                <option value="2">Cotización</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <label for="referencia_nombre">Nombre:</label>
                            <input type="text" id="referencia_nombre" name="referencia_nombre" class="form-control">
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <label for="referencia_serie">Serie:</label>
                            <input type="text" id="referencia_serie" name="referencia_serie" class="form-control">
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <label for="referencia_numero">Número:</label>
                            <input type="text" id="referencia_numero" name="referencia_numero" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <label for="referencia_desde">Desde:</label>
                            <input type="date" id="referencia_desde" name="referencia_desde" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <label for="referencia_hasta">Hasta:</label>
                            <input type="date" id="referencia_hasta" name="referencia_hasta" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <a onclick="mostar_referencias()" class="btn btn-xs btn-primary"> Mostrar</a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <table class="table table-bordered" id="listado_cotizacion_facturacion">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Sel</th>
                                <th>Documento</th>
                                <th>Fecha</th>
                                <th>Nombre / Razón Social</th>
                                <th>Glosa</th>
                            </tr>                            
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <br>
                <div class="row">
                    <table class="table table-bordered" id="listado_cotizacion_detalle_facturacion">
                        <thead>
                            <tr>
                                <th></ht>
                                <th>Sel</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>U.M.</th>
                                <th>Cantidad</th>
                            </tr>                            
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="button_acction" onclick="agregar_documentos_referencia()"><span class="fa fa-save"></span>
                    Agregar
                </button>
            </div>
        </div>
    </div>
</div>
