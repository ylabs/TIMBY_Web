<div class="one_full">
    <section class="title">
        <h4><?php echo lang('streams:reporttag.reports') ?></h4>
    </section>

    <section class="item">
        <div class="content">

            <!-- Show status of tags -->

            <?php if(! $tagged_reports) : ?>
                <div class="no_data"><?php echo lang('streams:reporttag.no_tags_done') ?></div>
            <?php endif; ?>

            <!-- Show reports and tags -->

            <?php if($all_reports) : ?>
                <table cellspacing="0">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?php echo lang('streams:reporttag.report') ?></th>
                        <th><?php echo lang('streams:reporttag.date') ?></th>
                        <th><?php echo lang('global:actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($all_reports as $item) : ?>
                        <tr>
                            <td><?php echo form_checkbox('stream_reporttag[]', $item->id, in_array($item->id, $tagged_reports_array)); ?></td>
                            <td>
                                <?php echo $item->title; ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y H:i:s", strtotime($item->report_date)); ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/timby/view/'.$item->id) ?>" title="<?php echo lang('global:view')?>" class="button" target="_blank"><?php echo lang('global:view')?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <div class="no_data"><?php echo lang('streams:reporttag.no_reports_uploaded'); ?></div>
            <?php endif; ?>
        </div>
    </section>
</div>