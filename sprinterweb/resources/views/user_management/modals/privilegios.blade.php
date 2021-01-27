<div class="modal fade" id="modal_add" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><span class="fa fa-check"></span> Actualizar Privilegios</h4>
            </div>
            <div class="modal-body">
                <form id="form_privilegios" name="form_privilegios" class="form-horizontal">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="ruta" name="ruta" value="{{ route('update_privilegios.user_management') }}">
                    <input type="hidden" id="var" name="var" value="{{ $var }}">
                    <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                    <input type="hidden" id="usuario_id" name="usuario_id">
                    <div class="row">
                        <div class="col-md-1">
                            <label for=""><b>Usuario</b></label>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="text" id="p_usuario" class="form-control" readonly>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <button type="button" class="form-control btn-success text-center" id="cargar_usuarios">
                                Cargar Privilegios de Usuario
                            </button>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <button type="button" class="form-control btn-primary text-center" id="aceptar">Aceptar
                            </button>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <button type="button" class="form-control btn-danger text-center" id="cancelar">Cancelar
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="sidebar-content" tabindex="0" style="background-color: #0095CC">
                                <nav class="sidebar-nav" dir="ltr">
                                    <div style="height: 45em; overflow: auto;">
                                        <ul class="nav side-menu">
                                            {!! \Menu2::buildMenu() !!}
                                        </ul>
                                    </div>

                                </nav>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-9">
                            <div id="style-1" style="height: 45em; overflow: auto;">
                                <div class="tab-content">
                                    <div id="#" class="tab-pane fade in active">
                                        <table class="table table-hover table-bordered table-dark table-responsive"
                                               id="tabla_privilegios">
                                            <thead class="text-center">
                                            <tr>
                                                <th>Opción</th>
                                                <th>Ver</th>
                                                <th>Crea</th>
                                                <th>Edita</th>
                                                <th>Anula</th>
                                                <th>Borra</th>
                                                <th>Print</th>
                                                <th>Aprob</th>
                                                <th>Precio</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    @foreach($maestros as $maestro)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$maestro->modulo).str_replace(' ','',$maestro->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.maestros.".eliminar_acentos(strtolower(str_replace(' ','',$maestro->modulo).str_replace(' ','',$maestro->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($logistica as $logistica)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$logistica->modulo).str_replace(' ','',$logistica->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.logistica.".eliminar_acentos(strtolower(str_replace(' ','',$logistica->modulo).str_replace(' ','',$logistica->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($ventas as $ventas)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$ventas->modulo).str_replace(' ','',$ventas->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.ventas.".eliminar_acentos(strtolower(str_replace(' ','',$ventas->modulo).str_replace(' ','',$ventas->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($compras as $compras)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$compras->modulo).str_replace(' ','',$compras->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.compras.".eliminar_acentos(strtolower(str_replace(' ','',$compras->modulo).str_replace(' ','',$compras->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($tesoreria as $tesoreria)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$tesoreria->modulo).str_replace(' ','',$tesoreria->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.tesoreria.".eliminar_acentos(strtolower(str_replace(' ','',$tesoreria->modulo).str_replace(' ','',$tesoreria->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($contabilidad as $contabilidad)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$contabilidad->modulo).str_replace(' ','',$contabilidad->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.contabilidad.".eliminar_acentos(strtolower(str_replace(' ','',$contabilidad->modulo).str_replace(' ','',$contabilidad->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($especial as $especial)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$especial->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.contabilidad.".eliminar_acentos(strtolower(str_replace(' ','',$especial->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($especial2 as $especial2)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$especial2->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.contabilidad.".eliminar_acentos(strtolower(str_replace(' ','',$especial2->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($activos as $activos)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$activos->modulo).str_replace(' ','',$activos->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.activos.".eliminar_acentos(strtolower(str_replace(' ','',$activos->modulo).str_replace(' ','',$activos->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($tributaria as $tributaria)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$tributaria->modulo).str_replace(' ','',$tributaria->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.tributaria.".eliminar_acentos(strtolower(str_replace(' ','',$tributaria->modulo).str_replace(' ','',$tributaria->descripcion))))
                                        </div>
                                    @endforeach
                                    {{--@foreach($planillas as $planillas)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$planillas->modulo).str_replace(' ','',$planillas->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.planillas.".eliminar_acentos(strtolower(str_replace(' ','',$planillas->modulo).str_replace(' ','',$planillas->descripcion))))
                                        </div>
                                    @endforeach
                                    @foreach($transporte as $transporte)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$transporte->modulo).str_replace(' ','',$transporte->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.transporte.".eliminar_acentos(strtolower(str_replace(' ','',$transporte->modulo).str_replace(' ','',$transporte->descripcion))))
                                        </div>
                                    @endforeach--}}
                                    @foreach($utilitarios as $utilitarios)
                                        <div id="{{eliminar_acentos(str_replace(' ','',$utilitarios->modulo).str_replace(' ','',$utilitarios->descripcion))}}" class="tab-pane fade">
                                            @include("user_management.tabs.utilitarios.".eliminar_acentos(strtolower(str_replace(' ','',$utilitarios->modulo).str_replace(' ','',$utilitarios->descripcion))))
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="agregar_item()" ><span class="fa fa-save"></span> Agregar Detalle</button>
            </div>--}}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

