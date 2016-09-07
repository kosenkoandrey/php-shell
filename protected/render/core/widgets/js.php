<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
<![endif]-->

<script>
    $(window).load(function () {
        /* --------------------------------------------------------
            Page Loader
         ---------------------------------------------------------*/
        if(!$('html').hasClass('ismobile')) {
            if($('.page-loader')[0]) {
                setTimeout (function () {
                    $('.page-loader').fadeOut();
                }, 500);

            }
        }
        
        /* --------------------------------------------------------
            User Alerts
        ----------------------------------------------------------*/
        $('body').on('click', '[data-user-alert]', function(e) {
            e.preventDefault();

            var u = $(this).data('user-alert');
            $('.'+u).tab('show');

        });


        /* ---------------------------------------------------------
             Todo Lists
         ----------------------------------------------------------*/
        if($('#todo-lists')[0]) {

            //Pre checked items
            $('#todo-lists .acc-check').each(function () {
                if($(this).is(':checked')) {
                    $(this).closest('.list-group-item').addClass('checked');
                }
            });

            //On check
            $('body').on('click', '#todo-lists .acc-check', function () {
               if($(this).is(':checked')) {
                   $(this).closest('.list-group-item').addClass('checked');
               }
                else {
                   $(this).closest('.list-group-item').removeClass('checked');
               }
            });
        }
    });
    
    $(document).ready(function(){
        /* --------------------------------------------------------
            Scrollbar
        ----------------------------------------------------------*/
        function scrollBar(selector, theme, mousewheelaxis) {
            $(selector).mCustomScrollbar({
                theme: theme,
                scrollInertia: 100,
                axis:'yx',
                mouseWheel: {
                    enable: true,
                    axis: mousewheelaxis,
                    preventDefault: true
                }
            });
        }

        if (!$('html').hasClass('ismobile')) {
            //On Custom Class
            if ($('.c-overflow')[0]) {
                scrollBar('.c-overflow', 'minimal-dark', 'y');
            }
        }
        
        /*
        * Auto Hight Textarea
        */
        if ($('.auto-size')[0]) {
            autosize($('.auto-size'));
        }
       
       /*
        * Text Feild
        */

        //Add blue animated border and remove with condition when focus and blur
        if ($('.fg-line')[0]) {
            $('body').on('focus', '.fg-line .form-control', function(){
                $(this).closest('.fg-line').addClass('fg-toggled');
            })

            $('body').on('blur', '.form-control', function(){
                var p = $(this).closest('.form-group, .input-group');
                var i = p.find('.form-control').val();

                if (p.hasClass('fg-float')) {
                    if (i.length == 0) {
                        $(this).closest('.fg-line').removeClass('fg-toggled');
                    }
                }
                else {
                    $(this).closest('.fg-line').removeClass('fg-toggled');
                }
            });
        }

        //Add blue border for pre-valued fg-flot text feilds
        if ($('.fg-float')[0]) {
            $('.fg-float .form-control').each(function(){
                var i = $(this).val();

                if (!i.length == 0) {
                    $(this).closest('.fg-line').addClass('fg-toggled');
                }

            });
        }

        /*
         * Tag Select
         */
        if ($('.chosen')[0]) {
            $('.chosen').chosen({
                width: '100%',
                allow_single_deselect: true
            });
        }

        /*
         * Input Slider
         */
        //Basic
        if ($('.input-slider')[0]) {
            $('.input-slider').each(function(){
                var isStart = $(this).data('is-start');

                $(this).noUiSlider({
                    start: isStart,
                    range: {
                        'min': 0,
                        'max': 100,
                    }
                });
            });
        }

        //Range slider
        if ($('.input-slider-range')[0]) {
            $('.input-slider-range').noUiSlider({
                start: [30, 60],
                range: {
                        'min': 0,
                        'max': 100
                },
                connect: true
            });
        }

        //Range slider with value
        if ($('.input-slider-values')[0]) {
            $('.input-slider-values').noUiSlider({
                start: [ 45, 80 ],
                connect: true,
                direction: 'rtl',
                behaviour: 'tap-drag',
                range: {
                        'min': 0,
                        'max': 100
                }
            });

            $('.input-slider-values').Link('lower').to($('#value-lower'));
            $('.input-slider-values').Link('upper').to($('#value-upper'), 'html');
        }

        /*
         * Input Mask
         */
        if ($('input-mask')[0]) {
            $('.input-mask').mask();
        }

        /*
         * Color Picker
         */
        if ($('.color-picker')[0]) {
                $('.color-picker').each(function(){
                var colorOutput = $(this).closest('.cp-container').find('.cp-value');
                $(this).farbtastic(colorOutput);
            });
        }

        /*
         * HTML Editor
         */
        if ($('.html-editor')[0]) {
               $('.html-editor').summernote({
                height: 150
            });
        }

        if ($('.html-editor-click')[0]) {
            //Edit
            $('body').on('click', '.hec-button', function(){
                $('.html-editor-click').summernote({
                    focus: true
                });
                $('.hec-save').show();
            })

            //Save
            $('body').on('click', '.hec-save', function(){
                $('.html-editor-click').code();
                $('.html-editor-click').destroy();
                $('.hec-save').hide();
                notify('Content Saved Successfully!', 'success');
            });
        }

        //Air Mode
        if ($('.html-editor-airmod')[0]) {
            $('.html-editor-airmod').summernote({
                airMode: true
            });
        }

        /*
         * Date Time Picker
         */

        //Date Time Picker
        if ($('.date-time-picker')[0]) {
               $('.date-time-picker').datetimepicker();
        }

        //Time
        if ($('.time-picker')[0]) {
            $('.time-picker').datetimepicker({
                format: 'LT'
            });
        }

        //Date
        if ($('.date-picker')[0]) {
            $('.date-picker').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        }

        /*
         * Form Wizard
         */
        if ($('.form-wizard-basic')[0]) {
            $('.form-wizard-basic').bootstrapWizard({
                tabClass: 'fw-nav',
                'nextSelector': '.next',
                'previousSelector': '.previous'
            });
        }

        /*
         * Bootstrap Growl - Notifications popups
         */
        function notify(message, type){
            $.growl({
                message: message
            },{
                type: type,
                allow_dismiss: false,
                label: 'Cancel',
                className: 'btn-xs btn-inverse',
                placement: {
                    from: 'top',
                    align: 'right'
                },
                delay: 2500,
                animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated bounceOut'
                },
                offset: {
                    x: 20,
                    y: 85
                }
            });
        };

        /*
         * Waves Animation
         */
        (function(){
            Waves.attach('.btn:not(.btn-icon):not(.btn-float)');
            Waves.attach('.btn-icon, .btn-float', ['waves-circle', 'waves-float']);
            Waves.init();
        })();

        /*
         * Lightbox
         */
        if ($('.lightbox')[0]) {
            $('.lightbox').lightGallery({
                enableTouch: true
            });
        }

        /*
         * Link prevent
         */
        $('body').on('click', '.a-prevent', function(e){
            e.preventDefault();
        });

        /*
         * Collaspe Fix
         */
        if ($('.collapse')[0]) {
            //Add active class for opened items
            $('.collapse').on('show.bs.collapse', function (e) {
                $(this).closest('.panel').find('.panel-heading').addClass('active');
            });

            $('.collapse').on('hide.bs.collapse', function (e) {
                $(this).closest('.panel').find('.panel-heading').removeClass('active');
            });

            //Add active class for pre opened items
            $('.collapse.in').each(function(){
                $(this).closest('.panel').find('.panel-heading').addClass('active');
            });
        }

        /*
         * Tooltips
         */
        if ($('[data-toggle="tooltip"]')[0]) {
            $('[data-toggle="tooltip"]').tooltip();
        }

        /*
         * Popover
         */
        if ($('[data-toggle="popover"]')[0]) {
            $('[data-toggle="popover"]').popover();
        }

        /*
         * Message
         */

        //Actions
        if ($('.on-select')[0]) {
            var checkboxes = '.lv-avatar-content input:checkbox';
            var actions = $('.on-select').closest('.lv-actions');

            $('body').on('click', checkboxes, function() {
                if ($(checkboxes+':checked')[0]) {
                    actions.addClass('toggled');
                }
                else {
                    actions.removeClass('toggled');
                }
            });
        }

        if ($('#ms-menu-trigger')[0]) {
            $('body').on('click', '#ms-menu-trigger', function(e){
                e.preventDefault();
                $(this).toggleClass('open');
                $('.ms-menu').toggleClass('toggled');
            });
        }
        
        /*
        * Fullscreen Browsing
        */
       if ($('[data-action="fullscreen"]')[0]) {
           var fs = $("[data-action='fullscreen']");
           fs.on('click', function(e) {
               e.preventDefault();

               //Launch
               function launchIntoFullscreen(element) {

                   if(element.requestFullscreen) {
                       element.requestFullscreen();
                   } else if(element.mozRequestFullScreen) {
                       element.mozRequestFullScreen();
                   } else if(element.webkitRequestFullscreen) {
                       element.webkitRequestFullscreen();
                   } else if(element.msRequestFullscreen) {
                       element.msRequestFullscreen();
                   }
               }

               //Exit
               function exitFullscreen() {

                   if(document.exitFullscreen) {
                       document.exitFullscreen();
                   } else if(document.mozCancelFullScreen) {
                       document.mozCancelFullScreen();
                   } else if(document.webkitExitFullscreen) {
                       document.webkitExitFullscreen();
                   }
               }

               launchIntoFullscreen(document.documentElement);
               fs.closest('.dropdown').removeClass('open');
           });
       }
       
       /*
        * IE 9 Placeholder
        */
       if ($('html').hasClass('ie9')) {
           $('input, textarea').placeholder({
               customClass: 'ie9-placeholder'
           });
       }

       /*
        * Print
        */
       if ($('[data-action="print"]')[0]) {
           $('body').on('click', '[data-action="print"]', function(e){
               e.preventDefault();
               window.print();
           });
       }

       /*
        * Actions
        */
       $('body').on('click', '[data-ma-action]', function (e) {
            e.preventDefault();

            var action = $(this).data('ma-action');
            var $this = $(this);

            switch (action) {

                /*-------------------------------------------
                    Mainmenu and Notifications open/close
                ---------------------------------------------*/

                /* Open Sidebar */
                case 'sidebar-open':

                    var target = $(this).data('ma-target');

                    $this.addClass('toggled');
                    $('#main').append('<div data-ma-action="sidebar-close" class="sidebar-backdrop animated fadeIn" />')

                    if (target == 'main-menu') {
                        $('#s-main-menu').addClass('toggled');
                    }
                    if (target == 'user-alerts') {
                        $('#s-user-alerts').addClass('toggled');
                    }

                    $('body').addClass('o-hidden');

                    break;

                /* Close Sidebar */
                case 'sidebar-close':

                    $('[data-ma-action="sidebar-open"]').removeClass('toggled');
                    $('.sidebar').removeClass('toggled');
                    $('.sidebar-backdrop').remove();
                    $('body').removeClass('o-hidden');

                    break;

                /*----------------------------------
                    Main menu
                -----------------------------------*/

                /* Toggle Sub menu */
                case 'submenu-toggle':

                    $this.next().slideToggle(200);
                    $this.parent().toggleClass('toggled');

                    break;



                /*----------------------------------
                     Messages
                -----------------------------------*/
                case 'message-toggle':

                    $('.ms-menu').toggleClass('toggled');
                    $this.toggleClass('toggled');

                    break;
            }
        }); 
    });
</script>