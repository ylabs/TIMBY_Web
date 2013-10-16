<div class="one_full">
    <section class="title">
        <h4><?php echo lang('mobileapi:users_title') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if($users) : ?>
                <table cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php echo lang('mobileapi:user') ?></th>
                        <th><?php echo lang('global:actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user->name; ?></td>
                            <td>
                                <a href="<?php echo site_url('admin/mobileapi/edit/'.$user->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a>
                                <a href="<?php echo site_url('admin/mobileapi/delete/'.$user->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="no_data"><?php echo lang('mobileapi:currently_no_users') ?></div>
            <?php endif; ?>
        </div>
    </section>
</div>