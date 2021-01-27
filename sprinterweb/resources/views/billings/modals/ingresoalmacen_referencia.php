<div class="modal fade" id="modalIngresoalmacenReferencia" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="color: black"><span class="fa fa-check"></span> Documentos de Referencia</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-salidasalmacen-referencia" name="form-salidasalmacen-referencia">
                    <div class="row">
                        <table class="table table-bordered" id="ingresoalmacen_referencia_cabecera">
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
                        <table class="table table-bordered" id="ingresoalmacen_referencia_detalle">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sel</th>
                                    <th>Recibir / Atender</th>
                                    <th>Documento</th>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>U.M.</th>
                                    <th>Solicitado</th>
                                    <th>Por Atender</th>
                                </tr>                            
                            </thead>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="button_acction" onclick="agregar_salidaalmacen_referencia()"><span class="fa fa-save"></span>
                    Agregar
                </button>
            </div>
        </div>
    </div>
</div>
