<div class="one_full">
    <section class="title">
        <h4><?php echo lang('mobileapi:keys_title') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if($keys) : ?>
                <table cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo lang('mobileapi:key') ?></th>
                            <th><?php echo lang('mobileapi:user') ?></th>
                            <th><?php echo lang('global:actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keys as $key) : ?>
                            <tr>
                                <?php echo form_open(site_url('admin/mobileapi/keys/assign_users')); ?>
                                    <input type='hidden' name='key_id' value='<?php echo($key->id); ?>'/>
                                    <td>
                                        <?php echo $key->key; ?>
                                    </td>
                                    <td>
                                        <?php if ($key->is_user_set) : ?>
                                            <?php echo $key->user_name; ?>
                                        <?php else :?>
                                            <?php echo form_dropdown('user_id', $users); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$key->is_user_set) : ?>
                                            <button type="submit"><?php echo lang('mobileapi:assign_to_users')?></button>
                                        <?php endif; ?>
                                        <a href="<?php echo site_url('admin/mobileapi/keys/delete/'.$key->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
                                    </td>
                                <?php echo form_close(); ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no_data"><?php echo lang('mobileapi:currently_no_keys') ?></div>
            <?php endif; ?>
        </div>
    </section>
</div>