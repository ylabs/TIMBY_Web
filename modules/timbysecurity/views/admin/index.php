<section class="title">
	<h4><?php echo lang('timbysecurity:dashboard'); ?></h4>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open('admin/timbysecurity/bulkactions');?>

        <?php if (!empty($items)): ?>

            <table>
                <thead>
                    <tr>
                        <th><?php echo lang('timbysecurity:log_id'); ?></th>
                        <th><?php echo lang('timbysecurity:section_id'); ?></th>
                        <th><?php echo lang('timbysecurity:description'); ?></th>
                        <th><?php echo lang('timbysecurity:date_and_time'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach( $items as $item ): ?>
                    <tr>
                        <td><?php echo($item->id); ?></td>
                        <td><?php echo($item->activity_id); ?></td>
                        <td><?php echo($item->description); ?></td>
                        <td><?php echo(date("dS F Y", strtotime($item->created_on))); ?></td>
                        <td class="actions"><?php echo lang('timbysecurity:no_actions'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="table_action_buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array())); ?>
            </div>

        <?php else: ?>
            <div class="no_data"><?php echo lang('timbysecurity:no_logs'); ?></div>
        <?php endif;?>

        <?php echo form_close(); ?>
    </div>
</section>