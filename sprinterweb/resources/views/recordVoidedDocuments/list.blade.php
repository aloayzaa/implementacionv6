@extends('templates.app')

@section('content')

    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{ route('create.recordVoidedDocument') }}" data-toggle="tooltip" data-placement="left"
                       title="Registrar" class="icon-button plus"><i class="fa fa-plus"></i><span></span></a>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" id="var" name="var" value="{{ $var }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                    <table id="tableRecordVoidedDocument"
                           class="table display responsive nowrap table-striped table-hover table-bordered"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Código</th>
                            <th>Razón social/Nombre Completo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <pre></pre>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableRecordVoidedDocument.init('{{ route('list.recordVoidedDocument') }}');
        });
    </script>
@endsection




