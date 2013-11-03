<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:reports') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if($items) : ?>
                <table cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php echo lang('timby:report') ?></th>
                        <th><?php echo lang('timby:date') ?></th>
                        <th><?php echo lang('global:actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item) : ?>
                        <tr>
                            <td>
                                <?php echo $item->title; ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y H:i:s", strtotime($item->slug)); ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/timby/view/'.$item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a>

                                <?php if($item->approved == 0) : ?>
                                    <a href="<?php echo site_url('admin/timby/approve/'.$item->id) ?>" title="<?php echo lang('timby:approve')?>" class="button"><?php echo lang('timby:approve')?></a>
                                <?php else : ?>
                                    <a href="<?php echo site_url('admin/timby/disapprove/'.$item->id) ?>" title="<?php echo lang('timby:disapprove')?>" class="button"><?php echo lang('timby:disapprove')?></a>
                                <?php endif; ?>

                                <a href="<?php echo site_url('admin/timby/delete/'.$item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no_data"><?php echo lang('timby:no_reports_uploaded') ?></div>
            <?php endif; ?>
        </div>
    </section>
</div>