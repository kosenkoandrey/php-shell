<!--=== Footer v8 ===-->
<div class="footer-v8">
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 column-one  margin-bottom-20">
                    <a href="<?= APP::Module('Routing')->root . Blog::URI ?>"><img class="footer-logo" src="<?= APP::Module('Routing')->root ?>public/blog/img/logo3-light.png"></a>
                    <p class="margin-bottom-20">Unify is an ultra fully responsive template with modern and smart design.</p>
                </div>
                
                <div class="col-md-3 col-sm-6">
                     <h2>Tags</h2>
                    <!-- Tag Links v4 -->
                    <ul class="tags-v4 margin-bottom-40">
                        <?
                        foreach (APP::Module('Blog')->RandomTags(10) as $value) {
                            ?><li><a class="rounded-4x" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>tag/<?= $value ?>"><?= $value ?></a></li><?
                        }
                        ?>
                    </ul>
                    <!-- End Tag Links v4 -->
                </div>

                <div class="col-md-3 offset10 offset-col-md-3 col-sm-6 md-margin-bottom-50">
                    <h2>Subscribe</h2>
                    <?
                    if (APP::Module('Users')->user['role'] == 'default') { 
                        ?>
                        <form id="subscribe-b">
                            <p><strong>Subscribe</strong> to our newsletter to be updated with the latest news and offers!</p><br>
                            <div id="subscribe-b-form">
                                <div class="input-group  margin-bottom-20">
                                    <input class="form-control" type="email" id="subscribe-b-email" name="email" placeholder="Email">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn-u input-btn">Subscribe</button>
                                    </div>
                                </div>
                                <div id="subscribe-b-error-email" style="display: none; color: #FFFFFF"></div>
                            </div>
                        </form>
                        <?
                    } else {
                        ?>
                        <p>You are subscribed to our newsletter with the latest news and offers!</p><br>
                        <p><b><i class="fa fa-envelope-o" aria-hidden="true"></i> <?= APP::Module('Users')->user['email'] ?></b></p>
                        <?
                    }
                    ?>   
                </div>
                
                <div class="col-md-3 col-sm-6 md-margin-bottom-50">
                     <h2>Social networks</h2>
                    <p><strong>Follow us</strong> on popular social networks</p><br>

                    <!-- Social Icons -->
                    <ul class="social-icon-list margin-bottom-40">
                        <li><a href="#" target="_blank"><i class="rounded-x fa fa-vk"></i></a></li>
                        <li><a href="#" target="_blank"><i class="rounded-x fa fa-twitter"></i></a></li>
                        <li><a href="#" target="_blank"><i class="rounded-x fa fa-facebook"></i></a></li>
                        <li><a href="#" target="_blank"><i class="rounded-x fa fa-google-plus"></i></a></li>
                    </ul>
                    <!-- End Social Icons -->
                </div>
            </div><!--/end row-->
        </div><!--/end container-->
    </footer>

    <footer class="copyright">
        <div class="container">
            <ul class="list-inline terms-menu">
                <li><?= date('Y') ?> &COPY; All rights reserved</li>
                <li><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>tos">Terms of Use</a></li>
                <li><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>privacy">Privacy and Policy</a></li>
            </ul>
        </div><!--/end container-->
    </footer>
</div>
<!--=== End Footer v8 ===-->