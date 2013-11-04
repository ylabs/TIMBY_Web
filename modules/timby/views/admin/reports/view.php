<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:report').': '.$item->title ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php

            foreach($item->objects as $object) :

                echo("<div style='padding-bottom:20px;'>");

                switch($object->type)
                {
                    case 0:
                        // Narrative
                        echo($object->narrative);
                        break;
                    case 1:
                        // Image
                        echo("<img src='".site_url(UPLOAD_PATH.'timby/images/'.$object->file)."'/>");
                        break;
                    case 2:
                        // Video
                        echo anchor(site_url(UPLOAD_PATH.'timby/videos/'.$object->file), lang('timby:download_video'), "target='_blank'");
                        break;
                }

                echo("</div>");

            endforeach;

            ?>
            <div style="padding-bottom">
                <?php if($item->approved == 0) : ?>
                    <a href="<?php echo site_url('admin/timby/approve/'.$item->id) ?>" title="<?php echo lang('timby:approve')?>" class="button"><?php echo lang('timby:approve')?></a>
                <?php else : ?>
                    <a href="<?php echo site_url('admin/timby/disapprove/'.$item->id) ?>" title="<?php echo lang('timby:disapprove')?>" class="button"><?php echo lang('timby:disapprove')?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>