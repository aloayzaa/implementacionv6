<div>
    <div class="col-sm-11 col-xs-12">
        <div class="x_panel">
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto ">
                <div class="">
                    <a href="{{ route('generales.configuration') }}">
                        <img src="{{url('graphics/generales.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>General</label>
            </div>

            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('maestros.configuration') }}">
                        <img src="{{url('graphics/catalogos.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Maestros</label>
            </div>

            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('compras.configuration') }}">
                        <img src="{{url('graphics/compras.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Compras</label>
            </div>

            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('ventas.configuration') }}">
                        <img src="{{url('graphics/ventas.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Ventas</label>
            </div>
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('logistica.configuration') }}">
                        <img src="{{url('graphics/logistica.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Almacenes</label>
            </div>
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('tesoreria.configuration') }}">
                        <img src="{{url('graphics/tesoreria.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Tesorería</label>
            </div>
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('contable.configuration') }}">
                        <img src="{{url('graphics/contable.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Contabilidad</label>
            </div>
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('activos.configuration') }}">
                        <img src="{{url('graphics/activos.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Activo Fijo</label>
            </div>

            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('planilla.configuration') }}">
                        <img src="{{url('graphics/rrhh.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Planillas</label>
            </div>
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('cpe.configuration') }}">
                        <img src="{{url('graphics/ctacte.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>CPE</label>
            </div>

            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('produccion.configuration') }}">
                        <img src="{{url('graphics/produccion.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Producción</label>
            </div>
            <div class="animated flipInY col-lg-1 col-md-1 col-sm-6 col-xs-12 centrar_texto">
                <div>
                    <a href="{{ route('transporte.configuration') }}">
                        <img src="{{url('graphics/transporte.gif')}}" alt="" class="img-responsive centrar_texto" width="50%">
                    </a>
                </div>
                <label>Transporte</label>
            </div>
        </div>
    </div>

    <div class="col-sm-1 col-xs-12">
        <button class="btn btn-primary btn-sm" id="btnGuardarConfiguracion{{ $varconf }}" name="btnGuardarConfiguracion{{ $varconf }}" style="margin-left: -7px;"><span class="fa fa-save"></span>&nbsp; <!--Guardar--></button>
        <button class="btn btn-danger btn-sm" onclick="recargar()" id="btnCancelarConfiguracion" name="btnCancelarConfiguracion" style="margin-left: -7px;"><span class="fa fa-times-circle"></span> <!--Cancelar--></button>
    </div>
</div>
