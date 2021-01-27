<aside class="sidebar-container" id="sidebar-min">
    <div class="sidebar-header" style="background-color: #0095CC;" id="sidebar">
        <div class="pull-right pt-lg text-muted hidden"><em class="ion-close-round"></em></div>
        <a class="sidebar-header-logo" style="color: #ffffff" href="#"><img src="{{ asset('img/sprinter.png') }}" alt="Sprinter Web"><span class="sidebar-header-logo-text">SPRINTER ERP</span></a>
        {{--<a href="#" id="menuSprinter" class="menu_toggle site_title"><img src="{{ asset('img/sprinter.png') }}">
            <span>SPRINTER WEB</span></a>--}}
        {{--<span class="sidebar-header-logo-text">SPRINTER WEB</span></a>--}}
        <div class="visible-xs visible-sm">
            <a class="sidebar-header-logo sidebar-sprinter" href="#"><span class="sidebar-header-logo-text"></span></a>
        </div>
    </div>
    <div class="sidebar-content"  tabindex="0">
            {{--
            <div class="sidebar-toolbar text-center"><a href=""><img class="img-circle thumb64" src="img/user/01.jpg"
                                                                     alt="Profile"></a>
                <div class="mt">Welcome, Willie Webb</div>
            </div>
            --}}
            <nav class="sidebar-nav" dir="ltr">
                <ul class="nav side-menu" style="margin-top: -15px; margin-bottom: 70px;">
                    {!! \Menu1::buildMenu() !!}
                </ul>

            </nav>
    </div>

    <div class="sidebar-footer hidden-small col-lg-12" id="menu">
        <ul>
            {!! $navbar_menu !!}
        </ul>
    </div>

</aside>
<!-- /sidebar menu -->
<div class="sidebar-layout-obfuscator"></div>
