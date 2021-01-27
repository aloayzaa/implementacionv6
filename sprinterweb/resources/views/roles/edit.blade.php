@extends('templates.app')
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT" data-route="{{ $route }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $roles->id }}">

                <div class="form-group">
                    <label class="control-label col-md-3" for="name">
                        Nombre
                    </label>
                    <div class="col-md-6 col-xs-12">
                        <input class="form-control" type="text" id="name" name="name" required
                               value="{{$roles->rol_nombre}}">
                    </div>
                </div>

                {{--<div class="form-group">
                    <label class="control-label col-md-3">
                        Permisos
                    </label>
                    <div class="col-md-6 col-xs-12">
                        @foreach($permissions as $permission)
                            <div class="icheck-peterriver">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       id="{{ $permission->id }}" disabled>
                                <label for="{{$permission->id }}">{{ $permission->per_nombre }} </label>
                            </div>
                        @endforeach
                    </div>
                </div>--}}
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/rol.js') }}"></script>
@endsection
