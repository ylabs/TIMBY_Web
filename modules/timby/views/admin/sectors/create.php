<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:create_category') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php echo form_open(site_url('admin/timby/sectors/create')); ?>
            <fieldset>
                <ul>
                    <li>
                        <label for="title"><?php echo lang('timby:sector') ?> <span>*</span></label>
                        <div class="input"><?php echo form_input('sector', '', 'maxlength="100" id="sector"') ?></div>
                    </li>

                    <div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?></div>
                </ul>
            </fieldset>
            <?php echo form_close(); ?>
        </div>
    </section>
</div>