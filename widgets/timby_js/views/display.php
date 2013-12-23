<script type="text/javascript">
    var sectors = {
        items:
            [
<?php foreach($sectors as $sector) : ?>
                {
                    item: '<?php echo $sector->sector; ?>',
                    id: '<?php echo $sector->id; ?>',
                    color: 'green'
                },
<?php endforeach; ?>
            ]
    }
</script>