<html>
    <head>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
    </head>
    <body>
        <h1>Ваша оценка была сохранена ранее. Оставьте свой комментарий.</h1>
        <form id="post-comment" method="post">
            <input type="hidden" name="id" value="<?= $data ?>">
            Как считаете, что мы можем сделать лучше?
            <br><br>
            <textarea id="comment" name="comment" class="form-control" rows="4"></textarea>
            <br>
            <input type="submit" class="control-btn btn btn-primary btn-lg" value="Send">
        </form>
    </body>
    <script>
        $(document).ready(function() {
            $('#post-comment').submit(function(event) {
                event.preventDefault();

                if ($(this).find('#comment').val() == '') {
                    return;
                }

                $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                $.ajax({
                    type: 'post',
                    url: '<?= APP::Module('Routing')->root ?>rating/comments/post.json',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result) {
                            $('body').html('<h1>Спасибо за ваш комментарий</h1>');
                        }

                        $('#post-comment').find('[type="submit"]').html('Send').attr('disabled', false);
                    }
                });
            });
        });
    </script>
</html>