@extends('templates.app')

@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            {{--
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{ route('create.otherprovisions') }}" data-toggle="tooltip" data-placement="left"
                       title="Registrar" class="icon-button plus"><i class="fa fa-plus"></i><span></span></a>
                </ul>
            </div>
            --}}
            <div class="x_content">
                <div id="datatable-responsive_wrapper"
                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" name="proceso" id="proceso"
                                   @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                            <input type="hidden" name="route" id="route"
                                   @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                            <input type="hidden" name="var" id="var"
                                   @if(isset($var)) value="{{$var}}" @else value="" @endif/>
                            <table id="listOtherProvisions"
                                   class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid"
                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 40%;" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">F. Proceso
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 40%;"
                                        aria-label="Last name: activate to sort column ascending">Documento
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 30%;"
                                        aria-label="Age: activate to sort column ascending">Fecha. Doc
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 25%;"
                                        aria-label="Age: activate to sort column ascending">Código
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Age: activate to sort column ascending">Razón Social
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Age: activate to sort column ascending">Mon
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Age: activate to sort column ascending">Total
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Age: activate to sort column ascending">Glosa
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Age: activate to sort column ascending">Estado
                                    </th>
                                    {{--
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 5%;"
                                        aria-label="Extn.: activate to sort column ascending">
                                    </th>
                                    --}}
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
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableOtherProvisions.init('{{ route('list.otherprovisions') }}');
        });
        $('#listOtherProvisions tbody').on('dblclick', 'tr', function () {
            var url = '{{route('edit.otherprovisions', ':id')}}';
            window.location = url.replace(':id', this.id);
        });
    </script>
@endsection
