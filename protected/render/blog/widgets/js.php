<script>
    $('#subscribe').submit(function(event) {
        event.preventDefault();

        var email = $('#subscribe-email');

        $('#subscribe-error-email').css('display','none').empty();

        if (email.val() === '') {
            $('#subscribe-error-email').css('display','block').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> E-Mail not set');
            return false;
        }

        $.ajax({
            type: 'post',
            url: '<?= APP::Module('Routing')->root ?>users/api/subscribe.json',
            data: $(this).serialize(),
            success: function(result) {
                switch (result.status) {
                    case 'error':
                        var errors = {
                            1: 'Invalid E-Mail',
                            2: 'E-Mail alerady exist',
                            3: 'Service turned off'
                        };
                        
                        $.each(result.errors, function(obj, code) {
                            $('#subscribe-error-email').css('display','block').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' + errors[code]);
                        });
                        break;
                    case 'success':
                        $('#subscribe-form')
                        .html(
                            $('<div/>')
                            .css({
                                'padding': '50px 0',
                                'font': '300 18px/27px "Open Sans", Helvetica, Arial, sans-serif',
                                'text-align': 'center',
                                'color': '#FFFFFF'
                            })
                            .append(
                                $('<i/>', {
                                    'class': 'rounded-x fa fa-check'
                                })
                                .css({
                                    'display': 'block',
                                    'margin': '0 auto 20px',
                                    'width': '81px',
                                    'height': '81px',
                                    'border': '1px solid #FFFFFF',
                                    'font-size': '30px',
                                    'line-height': '81px'
                                })
                            )
                            .append(
                                $('<p/>')
                                .css({
                                    'font-family': "Roboto Slab",
                                    'font-weight': 'bold',
                                    'font-size': '18px',
                                    'text-transform': 'uppercase'
                                })
                                .html('You are successfully subscribed')
                            )
                        );
                
                        setTimeout(function() {
                            $('#subscribe-popup').find('.g-popup__close').click();
                            location.reload();
                        }, 3000);
                        break;
                }
            }
        });
    });
      
    $('#subscribe-b').submit(function(event) {
        event.preventDefault();

        var email = $('#subscribe-b-email');

        $('#subscribe-b-error-email').css('display','none').empty();

        if (email.val() === '') {
            $('#subscribe-b-error-email').css('display','block').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> E-Mail not set');
            return false;
        }

        $.ajax({
            type: 'post',
            url: '<?= APP::Module('Routing')->root ?>users/api/subscribe.json',
            data: $(this).serialize(),
            success: function(result) {
                switch (result.status) {
                    case 'error':
                        var errors = {
                            1: 'Invalid E-Mail',
                            2: 'E-Mail alerady exist',
                            3: 'Service turned off'
                        };
                        
                        $.each(result.errors, function(obj, code) {
                            $('#subscribe-b-error-email').css('display','block').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' + errors[code]);
                        });
                        break;
                    case 'success':
                        $('#subscribe-b-form')
                        .css('color', '#FFFFFF')
                        .html('<i class="fa fa-check-circle" aria-hidden="true"></i> You are successfully subscribed');
                
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                }
            }
        });
    });
</script>