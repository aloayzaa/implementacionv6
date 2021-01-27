@extends('templates.app')
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            {{--
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{ route('create.adjustmentexchange') }}" data-toggle="tooltip" data-placement="left"
                       title="Registrar" class="icon-button plus"><i class="fa fa-plus"></i><span></span></a>
                </ul>
            </div>
            --}}
            <div class="x_content">
                <div id="datatable-responsive_wrapper"
                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                            <input type="hidden" name="route" id="route" @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                            <input type="hidden" name="var" id="var" @if(isset($var)) value="{{$var}}" @else value="" @endif/>

                            <table id="listOpeningSeat"
                                   class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th>Subdiario</th>
                                    <th>Voucher</th>
                                    <th>Fecha</th>
                                    <th>Mon</th>
                                    <th>T. Cambio</th>
                                    <th>Glosa</th>
                                    <th>Total M.N</th>
                                    <th>Total M.E</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableOpeningSeat.init('{{ route('list.adjustmentexchange') }}');
        });
        $('#listOpeningSeat tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.adjustmentexchange', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
