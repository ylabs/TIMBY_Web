<div class="one_full">
    <section class="title">
        <h4><?php echo lang('mobileapi:create_user') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php echo form_open(site_url('admin/mobileapi/create')); ?>
                <fieldset>
                    <ul>
                        <li>
                            <label for="title"><?php echo lang('mobileapi:user_name') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('user_name', '', 'maxlength="100" id="user_name"') ?></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('mobileapi:password') ?> <span>*</span></label>
                            <div class="input"><?php echo form_password('password', '', 'maxlength="100" id="password"') ?></div>
                        </li>

                        <div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?></div>
                    </ul>
                </fieldset>
            <?php echo form_close(); ?>
        </div>
    </section>
</div>