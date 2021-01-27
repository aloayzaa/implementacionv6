<script src="{{ asset('anikama/ani/home.js') }}"></script>


{{--<script>--}}
{{--    function checkWidth() {--}}
{{--        var windowSize = $(window).width();--}}

{{--        if (windowSize <= 479) {--}}
{{--            console.log("screen width is less than 480");--}}
{{--        }--}}
{{--        else if (windowSize <= 719) {--}}
{{--            console.log("screen width is less than 720 but greater than or equal to 480");--}}
{{--        }--}}
{{--        else if (windowSize <= 959) {--}}
{{--            console.log("screen width is less than 960 but greater than or equal to 720");--}}
{{--        }--}}
{{--        else if (windowSize >= 960) {--}}
{{--            console.log("screen width is greater than or equal to 960");--}}
{{--        }--}}
{{--    }--}}

{{--    // Execute on load--}}
{{--    checkWidth();--}}
{{--    // Bind event listener--}}
{{--    $(window).resize(checkWidth);--}}
{{--</script>--}}

<script src="{{ asset('js/template/jquery.mCustomScrollbar.concat.min.js') }}"></script>


<script>
    (function($){
        $(window).on("load",function(){
            $(".mCustomScrollbar").mCustomScrollbar({
                autoHideScrollbar: true,
                theme: "rounded"
            });
        });
    })(jQuery);
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            language: "es",
            theme: "classic"
        });


        $(".select3").select2({
            minimumInputLength: 2,
            language: "es",
            theme: "classic"
        });

        $(function () {
            $("[data-toggle=popover]").popover();
        });

        $(window).on('load', function () {
            $(".loader-page").css({visibility: "hidden", opacity: "0"})
        });

        $(function () {
            accountingPeriod.init('{{ route('accountingPeriod') }}', '{{ Session::get('period') }}' );
            salesPoint.init('{{ route('salesPoint') }}', '{{ Session::get('point') }}' );
        });

    });


    $('input[type="file"]').each(function(){
        // Refs
        var $file = $(this),
            $label = $file.next('label'),
            $labelText = $label.find('span'),
            labelDefault = $labelText.text();

        // When a new file is selected
        $file.on('change', function(event){
            var fileName = $file.val().split( '\\' ).pop(),
                tmppath = URL.createObjectURL(event.target.files[0]);
            //Check successfully selection
            if( fileName ){
                $label
                    .addClass('file-ok')
                    .css('background-image', 'url(' + tmppath + ')');
                $labelText.text(fileName);
            }else{
                $label.removeClass('file-ok');
                $labelText.text(labelDefault);
            }
        });
    });
</script>
