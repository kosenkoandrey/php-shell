jQuery(document).ready(function($) {
  var $form_modal = $('.cd-user-modal'),
    $form_login = $form_modal.find('#cd-login'),
    $form_signup = $form_modal.find('#cd-signup'),
    $form_forgot_password = $form_modal.find('#cd-reset-password'),
    $form_modal_tab = $('.cd-switcher'),
    $tab_login = $form_modal_tab.children('li').eq(0).children('a'),
    $tab_signup = $form_modal_tab.children('li').eq(1).children('a'),
    $forgot_password_link = $form_login.find('.cd-form-bottom-message a'),
    $back_to_login_link = $form_forgot_password.find('.cd-form-bottom-message a'),
    $main_nav = $('.cd-log_reg');

  //open modal
  $main_nav.on('click', function(event) {

    if ($(event.target).is($main_nav)) {
      // on mobile open the submenu
      $(this).children('ul').toggleClass('is-visible');
    } else {
      // on mobile close submenu
      $main_nav.children('ul').removeClass('is-visible');
      //show modal layer
      $form_modal.addClass('is-visible');
      //show the selected form
      ($(event.target).is('.cd-signup')) ? signup_selected() : login_selected();
    }

  });

  //close modal
  $('.cd-user-modal').on('click', function(event) {
    if ($(event.target).is($form_modal) || $(event.target).is('.cd-close-form')) {
      $form_modal.removeClass('is-visible');
    }
  });
  //close modal when clicking the esc keyboard button
  $(document).keyup(function(event) {
    if (event.which == '27') {
      $form_modal.removeClass('is-visible');
    }
  });

  //switch from a tab to another
  $form_modal_tab.on('click', function(event) {
    event.preventDefault();
    ($(event.target).is($tab_login)) ? login_selected() : signup_selected();
  });

  //hide or show password
  $('.hide-password').on('click', function() {
    var $this = $(this),
      $password_field = $this.prev('input');

    ('password' == $password_field.attr('type')) ? $password_field.attr('type', 'text') : $password_field.attr('type', 'password');
    ('Hide' == $this.text()) ? $this.text('Show') : $this.text('Hide');
    //focus and move cursor to the end of input field
    $password_field.putCursorAtEnd();
  });

  //show forgot-password form
  $forgot_password_link.on('click', function(event) {
    event.preventDefault();
    forgot_password_selected();
  });

  //back to login from the forgot-password form
  $back_to_login_link.on('click', function(event) {
    event.preventDefault();
    login_selected();
  });

  function login_selected() {
    $form_login.addClass('is-selected');
    $form_signup.removeClass('is-selected');
    $form_forgot_password.removeClass('is-selected');
    $tab_login.addClass('selected');
    $tab_signup.removeClass('selected');
  }

  function signup_selected() {
    $form_login.removeClass('is-selected');
    $form_signup.addClass('is-selected');
    $form_forgot_password.removeClass('is-selected');
    $tab_login.removeClass('selected');
    $tab_signup.addClass('selected');
  }

  function forgot_password_selected() {
    $form_login.removeClass('is-selected');
    $form_signup.removeClass('is-selected');
    $form_forgot_password.addClass('is-selected');
  }

  //REMOVE THIS - it's just to show error messages
  $form_login.find('form').submit(function(event) {
    event.preventDefault();
    $form_login.find('#signin-email').removeClass('has-error').next('span').removeClass('is-visible').empty();

    $.ajax({
        type: 'post',
        url: '/users/api/login.json',
        data: $(this).serialize(),
        success: function(result) {
            switch (result.status) {
                case 'error':
                    $form_login.find('#signin-email').addClass('has-error').next('span').addClass('is-visible').html('Invalid login information');
                    break;
                case 'success':
                    document.location.href = '/';
                    break;
            }
        }
    });
  });
  
  $form_signup.find('form').submit(function(event) {
    event.preventDefault();
    
    var email = $form_signup.find('#signup-email');
    var password = $form_signup.find('#signup-password');
    var re_password = $form_signup.find('#signup-re-password');
    
    email.removeClass('has-error').siblings('span').removeClass('is-visible').empty();
    password.removeClass('has-error').siblings('span').removeClass('is-visible').empty();
    re_password.removeClass('has-error').siblings('span').removeClass('is-visible').empty();
    
    if (email.val() == '') {
        email.addClass('has-error').siblings('span').addClass('is-visible').html('E-Mail not set');
        return false;
    }
    
    if (password.val() == '') {
        password.addClass('has-error').siblings('span').addClass('is-visible').html('Password not set');
        return false;
    }
    
    if (password.val() != re_password.val()) {
        re_password.addClass('has-error').siblings('span').addClass('is-visible').html('Passwords do not match');
        return false;
    }

    $.ajax({
        type: 'post',
        url: '/users/api/register.json',
        data: $(this).serialize(),
        success: function(result) {
            switch (result.status) {
                case 'error':
                    $.each(result.errors, function(obj, code) {
                        switch(code) {
                            case 1: $('#signup-email').addClass('has-error').siblings('span').addClass('is-visible').html('Invalid E-Mail'); break;
                            case 2: $('#signup-email').addClass('has-error').siblings('span').addClass('is-visible').html('E-Mail alerady registered'); break;
                            case 3: $('#signup-password').addClass('has-error').siblings('span').addClass('is-visible').html('Password not set'); break;
                            case 4: $('#signup-password').addClass('has-error').siblings('span').addClass('is-visible').html('Password is too short'); break;
                            case 5: $('#signup-password').addClass('has-error').siblings('span').addClass('is-visible').html('Passwords do not match'); break;
                            case 6: alert('Service turned off'); break;
                        }
                    });
                    break;
                case 'success':
                    $form_signup
                    .html(
                        $('<div/>')
                        .css({
                            'padding': '50px',
                            'font': '300 18px/27px "Open Sans", Helvetica, Arial, sans-serif',
                            'text-align': 'center',
                            'color': '#6fb679'
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
                                'border': '1px solid #6fb679',
                                'font-size': '30px',
                                'line-height': '81px'
                            })
                        )
                        .append(
                            $('<p/>').html('You have successfully registered<br><small>Activation link sent to your E-Mail</small>')
                        )
                    );
                    break;
            }
        }
    });
  });
  
  $form_forgot_password.find('form').submit(function(event) {
    event.preventDefault();
    
    var email = $form_forgot_password.find('#reset-email');
    
    email.removeClass('has-error').siblings('span').removeClass('is-visible').empty();
    
    if (email.val() == '') {
        email.addClass('has-error').siblings('span').addClass('is-visible').html('E-Mail not set');
        return false;
    }

    $.ajax({
        type: 'post',
        url: '/users/api/reset-password.json',
        data: $(this).serialize(),
        success: function(result) {
            switch (result.status) {
                case 'error':
                    $.each(result.errors, function(obj, code) {
                        switch(code) {
                            case 1: $('#reset-email').addClass('has-error').siblings('span').addClass('is-visible').html('Invalid E-Mail'); break;
                            case 2: $('#reset-email').addClass('has-error').siblings('span').addClass('is-visible').html('E-Mail is not registered'); break;
                            case 3: $('#reset-email').addClass('has-error').siblings('span').addClass('is-visible').html('Service turned off'); break;
                        }
                    });
                    break;
                case 'success':
                    $form_forgot_password
                    .html(
                        $('<div/>')
                        .css({
                            'padding': '50px',
                            'font': '300 18px/27px "Open Sans", Helvetica, Arial, sans-serif',
                            'text-align': 'center',
                            'color': '#6fb679'
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
                                'border': '1px solid #6fb679',
                                'font-size': '30px',
                                'line-height': '81px'
                            })
                        )
                        .append(
                            $('<p/>').html('Link sent successfully<br><small>Link to restore access sent to your E-Mail</small>')
                        )
                    );
                    break;
            }
        }
    });
  });


  //IE9 placeholder fallback
  //credits http://www.hagenburger.net/BLOG/HTML5-Input-Placeholder-Fix-With-jQuery.html
  if (!Modernizr.input.placeholder) {
    $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.val(input.attr('placeholder'));
      }
    }).blur();
    $('[placeholder]').parents('form').submit(function() {
      $(this).find('[placeholder]').each(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
          input.val('');
        }
      })
    });
  }

});


//credits http://css-tricks.com/snippets/jquery/move-cursor-to-end-of-textarea-or-input/
jQuery.fn.putCursorAtEnd = function() {
  return this.each(function() {
    // If this function exists...
    if (this.setSelectionRange) {
      // ... then use it (Doesn't work in IE)
      // Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
      var len = $(this).val().length * 2;
      this.setSelectionRange(len, len);
    } else {
      // ... otherwise replace the contents with itself
      // (Doesn't work in Google Chrome)
      $(this).val($(this).val());
    }
  });
};
