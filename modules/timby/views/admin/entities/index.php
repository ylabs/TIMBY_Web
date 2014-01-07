<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:entities') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if($items) : ?>
                <table cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php echo lang('timby:entity') ?></th>
                        <th><?php echo lang('timby:slug') ?></th>
                        <th><?php echo lang('global:actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item) : ?>
                        <tr>
                            <td>
                                <?php echo $item->entity; ?>
                            </td>
                            <td>
                                <?php echo $item->slug; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/timby/entities/edit/'.$item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a>
                                <a href="<?php echo site_url('admin/timby/entities/delete/'.$item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no_data"><?php echo lang('timby:no_entities') ?></div>
            <?php endif; ?>
        </div>
    </section>
</div>