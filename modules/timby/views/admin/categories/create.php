<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:create_category') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php echo form_open(site_url('admin/timby/categories/create')); ?>
            <fieldset>
                <ul>
                    <li>
                        <label for="title"><?php echo lang('timby:category') ?> <span>*</span></label>
                        <div class="input"><?php echo form_input('category', '', 'maxlength="100" id="category"') ?></div>
                    </li>

                    <div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?></div>
                </ul>
            </fieldset>
            <?php echo form_close(); ?>
        </div>
    </section>
</div>