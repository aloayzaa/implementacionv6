/*! *
 * Centric - Bootstrap Admin Template
 *
 * Version: 2.0
 * Author: @themicon_co
 * Website: http://themicon.co
 * License: https://wrapbootstrap.com/help/licenses
 *
 */

// APP START
// -----------------------------------
(function() {
    'use strict';

    $(initSettings);

    function initSettings() {

        // Themes setup
        var themes = [
            'theme-1',
            'theme-2',
            'theme-3',
            'theme-4',
            'theme-5',
            'theme-6',
            'theme-7',
            'theme-8',
            'theme-9'
        ];

        var body = $('body');
        var header = $('.layout-container > header');
        var sidebar = $('.layout-container > aside');
        var brand = sidebar.find('.sidebar-header');
        var content = $('.layout-container > main');

        // Handler for themes preview
        $('input[name="setting-theme"]:radio').change(function() {
            var index = this.value;
            if (themes[index]) {
                body.removeClass(themeClassname);
                body.addClass(themes[index]);
            }
        });
        // Regular expression for the pattern bg-* to find the background class
        function themeClassname(index, css) {
            var cmatch = css.match(/(^|\s)theme-\S+/g);
            return (cmatch || []).join(' ');
        }


        // Handler for menu links
        $('input[name="headerMenulink"]:radio').change(function() {
            var menulinks = $('.menu-link');
            // remove allowed classses
            menulinks.removeClass('menu-link-slide menu-link-arrow menu-link-close');
            // Add selected
            menulinks.addClass(this.value);
        });

        // Handlers for layout variations
        // var lContainer = $('.layout-container');
        $('#sidebar-showheader').change(function() {
            brand[this.checked ? 'show' : 'hide']();
        });
        var sidebarToolbar = $('.sidebar-toolbar');
        $('#sidebar-showtoolbar').change(function() {
            sidebarToolbar[this.checked ? 'show' : 'hide']();
        });

        $('#sidebar-offcanvas').change(function() {
            body[this.checked ? 'addClass' : 'removeClass']('sidebar-offcanvas');
        });
    }


})();
(function() {
    'use strict';

    $(sidebarNav);

    function sidebarNav() {

        var $sidebarNav = $('.sidebar-nav');
        var $sidebarContent = $('.sidebar-content');

        activate($sidebarNav);

        $sidebarNav.on('click', function(event) {
            var item = getItemElement(event);
            // check click is on a tag
            if (!item) return;

            var ele = $(item),
                liparent = ele.parent()[0];

            var lis = ele.parent().parent().children(); // markup: ul > li > a
            // remove .active from childs
            lis.find('li').removeClass('active');
            // remove .active from siblings ()
            $.each(lis, function(idx, li) {
                if (li !== liparent)
                    $(li).removeClass('active');
            });

            var next = ele.next();
            if (next.length && next[0].tagName === 'UL') {
                ele.parent().toggleClass('active');
                event.preventDefault();
            }
        });

        // find the a element in click context
        // doesn't check deeply, asumens two levels only
        function getItemElement(event) {
            var element = event.target,
                parent = element.parentNode;
            if (element.tagName.toLowerCase() === 'a') return element;
            if (parent.tagName.toLowerCase() === 'a') return parent;
            if (parent.parentNode.tagName.toLowerCase() === 'a') return parent.parentNode;
        }
        /*
        function activate(sidebar) {
            sidebar.find('a').each(function() {
                var href = $(this).attr('href').replace('#', '');
                if (href !== '' && window.location.href.indexOf(href) >= 0) {
                    var item = $(this).parents('li').addClass('active');
                    // Animate scrolling to focus active item
                    // $sidebarContent.animate({
                    //     scrollTop: $sidebarContent.scrollTop() + item.position().top
                    // }, 1200);
                    return false; // exit foreach
                }
            });
        }
        */
        /*
        function activate(sidebar) {
            var LocationActual = window.location;

            sidebar.find('a').each(function() {
                var href = $(this).attr('href').replace('#', '');
                if (LocationActual == href) { //compara la ruta actual con la ruta seleccionada
                    var item = $(this).parents('li').addClass('active');

                    return false; // exit foreach
                }
            });
        }
        */
        //nuevo metodo 1 de active a
        function activate(sidebar) {
            var location_actual = window.location;
            var location_array = String(location_actual).split("/",4);
            var comparar = location_array[0]+"/"+location_array[1]+"/"+location_array[2]+"/"+location_array[3];

            sidebar.find('a').each(function() {
                var href = $(this).attr('href').replace('#', '');
                if (comparar == href) { //compara la ruta actual con la ruta seleccionada
                    var item = $(this).parents('li').addClass('active');
                    return false; // exit foreach
                }
            });
        }
        /*-----------------------*/
        var layoutContainer = $('.layout-container');
        var $body = $('body');
        // Handler to toggle sidebar visibility on mobile
        $('#sidebar-toggler').click(function(e) {
            e.preventDefault();
            layoutContainer.toggleClass('sidebar-visible');
            // toggle icon state
            $(this).parent().toggleClass('active');
        });
        // Close sidebar when click on backdrop
        $('.sidebar-layout-obfuscator').click(function(e) {
            e.preventDefault();
            layoutContainer.removeClass('sidebar-visible');
            // restore icon
            $('#sidebar-toggler').parent().removeClass('active');
        });

        //AGREGADO SPRINTER

        $('.sidebar-sprinter').click(function(e) {
            e.preventDefault();
            layoutContainer.removeClass('sidebar-visible');
            // restore icon
            $('#sidebar-toggler').parent().removeClass('active');
        });

        //FIN


        // Handler to toggle sidebar visibility on desktop
        $('#offcanvas-toggler').click(function(e) {
            e.preventDefault();
            $body.toggleClass('offcanvas-visible');
            // toggle icon state
            $(this).parent().toggleClass('active');
        });

        // remove desktop offcanvas when app changes to mobile
        // so when it returns, the sidebar is shown again
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                $body.removeClass('offcanvas-visible');
                $('#offcanvas-toggler').parent().addClass('active');
            }
        });

    }

})();
function data_menu(empresa, ruc, usuario){
    if (!$('#menu-open').prop('checked')){
        $('#estadito').hide();
        $('#data-empresa').addClass('data-empresa-open')
        $('#data-empresa').html("<p style='/*height: 35px;position: absolute; padding:20px 70px; font-size: 12px; color: black;'>" +
            "<span style='font-weight:bold;'>Empresa:</span> " + empresa + " │ " +
            "<span style='font-weight:bold;'>RUC:</span> " + ruc + " │ " +
            "<span style='font-weight:bold;'>Usuario:</span> " + usuario + "</p>");
    }
    if ($('#menu-open').prop('checked')){
        $('#estadito').show();
        $('#data-empresa').removeClass('data-empresa-open')
        $('#data-empresa').empty();
        //document.getElementById('data-empresa').innerHTML = '';
    }
}
/*----------boton menu---------*/
var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
var hamburgers = document.querySelectorAll(".hamburgerr");
if (hamburgers.length > 0) {
    forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function() {
            this.classList.toggle("is-active");
        }, false);
    });
}
/*-----------------------------*/
