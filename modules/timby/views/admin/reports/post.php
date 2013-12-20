<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:edit_report_post') ?></h4>
    </section>

    <?php echo form_open(site_url("admin/timby/post/{$report_id}")); ?>
        <section class="item">
            <div class="content">
                <?php echo form_textarea(array('id' => 'post', 'name' => 'post', 'value' => $post_text, 'rows' => 30, 'class' => 'wysiwyg-advanced')) ?>
                <p>
                    <input type="submit" class="button" value="<?php echo lang('save_label'); ?>"/>
                </p>
            </div>
        </section>
    <?php echo form_close(); ?>
</div>