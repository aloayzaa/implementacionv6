<div class="modal fade" id="modal_debito_credito" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="color: black"><span class="fa fa-check"></span> Documentos de Referencia</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-credito-debito" name="form-credito-debito">
                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <label for="creditodebito_serie">Serie:</label>
                            <input type="text" id="creditodebito_serie" name="creditodebito_serie" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <label for="creditodebito_numero">Número:</label>
                            <input type="text" id="creditodebito_numero" name="creditodebito_numero" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <label for="creditodebito_desde">Desde:</label>
                            <input type="date" id="creditodebito_desde" name="creditodebito_desde" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <label for="creditodebito_hasta">Hasta:</label>
                            <input type="date" id="creditodebito_hasta" name="creditodebito_hasta" class="form-control">
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <a onclick="mostrar_credito_debito()" class="btn btn-xs btn-primary"> Mostrar</a>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-bordered" id="listado_notacreditodebito_cabecera">
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
                        <table class="table table-bordered" id="listado_notacreditodebito_detalle">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sel</th>
                                    <th>Aplicar</th>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>U.M.</th>
                                    <th>Cantidad</th>
                                    <th>Lote</th>
                                    <th>Serie</th>
                                </tr>                            
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="button_acction" onclick="agregar_credito_debito()"><span class="fa fa-save"></span>
                    Agregar
                </button>
            </div>
        </div>
    </div>
</div>
