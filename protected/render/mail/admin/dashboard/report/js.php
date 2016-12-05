<script>
    function tab_<?= $data['hash'] ?>() {
        $('#tab-<?= $data['hash'] ?>').html('<?= $data['hash'] ?>');
    }
    
    tab_<?= $data['hash'] ?>();
</script>