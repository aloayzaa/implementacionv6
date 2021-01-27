@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <ul class="nav nav-tabs bar_tabs">
                <li class="active">
                    <a href="#tab_content1" id="home-tab" data-toggle="tab">
                        General
                    </a>
                </li>
                @if($proceso == 'show')
                    <li role="presentation" class="">
                        <a href="#tab_content2" id="profile-tab" data-toggle="tab">
                            Aplicaciones
                        </a>
                    </li>
                @endif
            </ul>

            <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="tab_content1">
                    @include('exitToWarehouse.tabs.general')
                </div>
            </div>

            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade " id="tab_content2">
                    <h1>aaaaa</h1>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/buy.js') }}"></script>
@endsection
