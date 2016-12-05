<div class="cd-user-modal"> <!-- this is the entire modal form, including the background -->
    <div class="cd-user-modal-container"> <!-- this is the container wrapper -->
        <ul class="cd-switcher">
            <li><a href="javascript:void(0);">Login</a></li>
            <li><a href="javascript:void(0);">Register</a></li>
        </ul>

        <div id="cd-login"> <!-- log in form -->
            <div class="lined-text"><span>Sign in with</span><hr></div>
            <form class="cd-form">
                <p class="social-login">
                    <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_vk_id'])) { ?><span class="social-login-vk"><a href="http://oauth.vk.com/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_vk_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/vk', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode(APP::Module('Routing')->root . 'users/profile') . '"}')])) ?>"><i class="fa fa-vk"></i> VK</a></span><? } ?>
                    <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_fb_id'])) { ?><span class="social-login-facebook"><a href="https://www.facebook.com/dialog/oauth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_fb_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/fb', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode(APP::Module('Routing')->root . 'users/profile') . '"}')])) ?>"><i class="fa fa-facebook"></i> Facebook</a></span><? } ?>
                    <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_google_id'])) { ?><span class="social-login-google"><a href="https://accounts.google.com/o/oauth2/auth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_google_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/google', 'response_type' => 'code', 'scope' => urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'), 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode(APP::Module('Routing')->root . 'users/profile') . '"}')])) ?>"><i class="fa fa-google"></i> Google</a></span><? } ?>
                </p>

                <div class="lined-text" style="margin-top: 15px"><span>or use account</span></div>

                <p class="fieldset">
                    <label class="image-replace cd-email" for="signin-email">E-mail</label>
                    <input class="full-width has-padding has-border" id="signin-email" type="email" name="email" placeholder="E-mail" value="<?= isset($_COOKIE['modules']['users']['email']) ? $_COOKIE['modules']['users']['email'] : '' ?>">
                    <span class="cd-error-message"></span>
                </p>

                <p class="fieldset">
                    <label class="image-replace cd-password" for="signin-password">Password</label>
                    <input class="full-width has-padding has-border" id="signin-password" type="password" name="password" placeholder="Password">
                    <a href="javascript:void(0);" class="hide-password">Show</a>
                </p>

                <p class="fieldset">
                    <input type="checkbox" id="remember-me" name="remember-me" checked>
                    <label for="remember-me">Remember me</label>
                </p>

                <p class="fieldset">
                    <input class="full-width" type="submit" value="Login">
                </p>
            </form>

            <p class="cd-form-bottom-message"><a href="javascript:void(0);">Forgot your password?</a></p>
            <!-- <a href="javascript:void(0);" class="cd-close-form">Close</a> -->
        </div> <!-- cd-login -->

        <div id="cd-signup"> <!-- sign up form -->
            <div class="lined-text"><span>Register with</span><hr></div>
            <form class="cd-form">
                <p class="social-login">
                    <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_vk_id'])) { ?><span class="social-login-vk"><a href="http://oauth.vk.com/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_vk_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/vk', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode(APP::Module('Routing')->root . 'users/profile') . '"}')])) ?>"><i class="fa fa-vk"></i> VK</a></span><? } ?>
                    <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_fb_id'])) { ?><span class="social-login-facebook"><a href="https://www.facebook.com/dialog/oauth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_fb_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/fb', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode(APP::Module('Routing')->root . 'users/profile') . '"}')])) ?>"><i class="fa fa-facebook"></i> Facebook</a></span><? } ?>
                    <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_google_id'])) { ?><span class="social-login-google"><a href="https://accounts.google.com/o/oauth2/auth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_google_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/google', 'response_type' => 'code', 'scope' => urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'), 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode(APP::Module('Routing')->root . 'users/profile') . '"}')])) ?>"><i class="fa fa-google"></i> Google</a></span><? } ?>
                </p>

                <div class="lined-text" style="margin-top: 15px"><span>or fill out the registration form yourself</span></div>

                <p class="fieldset">
                    <label class="image-replace cd-email" for="signup-email">E-mail</label>
                    <input class="full-width has-padding has-border" id="signup-email" type="email" name="email" placeholder="E-mail">
                    <span class="cd-error-message"></span>
                </p>

                <p class="fieldset">
                    <label class="image-replace cd-password" for="signup-password">Password</label>
                    <input class="full-width has-padding has-border" id="signup-password" type="password" name="password"  placeholder="Password">
                    <a href="javascript:void(0);" class="hide-password">Show</a>
                    <span class="cd-error-message"></span>
                </p>

                <p class="fieldset">
                    <label class="image-replace cd-password" for="signup-password">Retype password</label>
                    <input class="full-width has-padding has-border" id="signup-re-password" type="password" name="re-password"  placeholder="Retype password">
                    <a href="javascript:void(0);" class="hide-password">Show</a>
                    <span class="cd-error-message"></span>
                </p>

                <p class="fieldset">
                    <input type="checkbox" checked="checked" disabled="disabled">
                    <label for="accept-terms">Accept <a target="_blank" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>tos">terms of service</a></label>
                </p>

                <p class="fieldset">
                    <input class="full-width has-padding" type="submit" value="Create account">
                </p>
            </form>
            <!-- <a href="javascript:void(0);" class="cd-close-form">Close</a> -->
        </div> <!-- cd-signup -->

        <div id="cd-reset-password"> <!-- reset password form -->
            <p class="cd-form-message">Forgot your password? Please enter your E-Mail address, the link will be sent to him to set a new password.</p>

            <form class="cd-form">
                <p class="fieldset">
                    <label class="image-replace cd-email" for="reset-email">E-mail</label>
                    <input class="full-width has-padding has-border" id="reset-email" name="email" type="email" placeholder="E-mail">
                    <span class="cd-error-message"></span>
                </p>

                <p class="fieldset">
                    <input class="full-width has-padding" type="submit" value="Restore password">
                </p>
            </form>

            <p class="cd-form-bottom-message"><a href="javascript:void(0);">Return to login form</a></p>
        </div> <!-- cd-reset-password -->
        <a href="javascript:void(0);" class="cd-close-form">Close</a>
    </div> <!-- cd-user-modal-container -->
</div> <!-- cd-user-modal -->