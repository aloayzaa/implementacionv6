<div class="modal fade" id="modal_add" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="color: black"><span class="fa fa-check"></span> Detalle Movimiento de Banco</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-detail" class="form-horizontal">
                    <input type="hidden" id="rowId" name="rowId">
                    <div class="form-group">
                        <div class="col-sm-4 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Operación</label>
                            <div class="col-sm-12 col-xs-12">
                                <select class="select2" name="operation" id="operation">
                                    <option value="">- Seleccione -</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Tercero</label>
                            <div class="col-sm-12 col-xs-12">
                                <select class="select2" name="customer" id="customer">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Hasta</label>
                            <div class="col-sm-12 col-xs-12">
                                <input type="date" name="finaldate" id="finaldate" class="form-control"
                                       autocomplete="off" min="{{$period->inicio}}" max="{{$period->final}}">
                            </div>
                        </div>

                        <div class="col-sm-1 col-xs-12">
                            <label class="col-sm-12 col-xs-12">&nbsp;</label>
                            <button type="button" name="buscar" id="buscar" class="btn btn-sm btn-primary buscar"
                                    onclick="referencetable()">Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <div class="ln_solid"></div>
                    </div>
                    <table id="listCashMovementReference"
                           class="table-reference table-striped table-bordered" width="100%">
                        <thead>
                        <tr role="row">
                            <th></th>
                            <th></th>
                            <th>Aplicar</th>
                            <th>Documento</th>
                            <th>Fecha</th>
                            <th>Vence</th>
                            <th>Mon</th>
                            <th>Saldo M.N</th>
                            <th>Saldo M.E</th>
                            <th>Glosa</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <b>Total:</b>
                        <input type="text" id="total_detalle_referencia" value="0" style="border: 0">
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <div class="ln_solid"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Importe</label>
                            <div class="col-sm-12 col-xs-12">
                                <input type="number" name="amount" id="amount"
                                       class="form-control" value="0.00">
                            </div>
                        </div>
                        {{--depende si es agente de retención--}}
                        <div class="col-sm-3 col-xs-12">
                            <div id="ver_cci">
                                <label class="col-sm-12 col-xs-12" for="txt_cci">CCI:</label>
                                <div class="col-sm-12 col-xs-12">
                                    <input type="text" name="txt_cci" id="txt_cci"
                                           class="form-control" value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Cuenta</label>
                            <div class="col-sm-12 col-xs-12">
                                <select class="form-control select2" name="account" id="account">
                                    <option selected value="">-Seleccione-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <label class="col-sm-12 col-xs-12">C. Costo</label>
                            <div class="col-sm-12 col-xs-12">
                                <select class="form-control select2" name="cost" id="cost">
                                    <option selected value="">-Seleccione-</option>
                                    @foreach($costos as $costo)
                                        <option value="{{$costo->id}}">
                                            {{$costo->codigo}} | {{$costo->descripcion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Actividad</label>
                            <div class="col-sm-12 col-xs-12">
                                <select class="select2" name="activity" id="activity">
                                    <option value="" disabled selected>-Seleccionar-</option>
                                    @foreach($actividades as $actividad)
                                        <option value="{{$actividad->id}}">
                                            {{ $actividad->codigo }} | {{ $actividad->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <label class="col-sm-12 col-xs-12">Proyecto</label>
                            <div class="col-sm-12 col-xs-12">
                                <select class="select2" name="project" id="project">
                                    <option value="" disabled selected>-Seleccionar-</option>
                                    @foreach($proyectos as $proyecto)
                                        <option value="{{$proyecto->id}}">
                                            {{ $proyecto->codigo }} | {{ $proyecto->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="button_acction" onclick="agregar_item()"><span class="fa fa-save"></span>
                    Agregar Detalle
                </button>
            </div>
        </div>
    </div>
</div>
