<style type="text/css">
    #map { height: 300px; }
</style>

<div class="one_full">
    <section class="title">
        <h4><?php echo lang('timby:report').': '.$item->title ?></h4>
    </section>

    <section class="item">
        <div class="content">

            <div id="map" style="margin-top:10px; margin-bottom:10px;"></div>

            <script type="text/javascript">
                // create a map in the "map" div, set the view to a given place and zoom
                var map = L.map('map').setView({lat: <?php echo $item->lat; ?>, lon: <?php echo $item->long; ?>}, 13);

                // add an OpenStreetMap tile layer
                L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // add a marker in the given location, attach some popup content to it and open the popup
                L.marker({lat: <?php echo $item->lat; ?>, lon: <?php echo $item->long; ?>}).addTo(map)
                    .bindPopup('<?php echo lang('timby:report_point'); ?>')
                    .openPopup();
            </script>

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
                        echo("<img src='".site_url(UPLOAD_PATH.'timby/images/'.$object->file)."' style='width:100%;'/>");
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
                <a target="_blank" href="<?php echo site_url('admin/timby/post/'.$item->id) ?>" title="<?php echo lang('timby:report_post')?>" class="button"><?php echo lang('timby:report_post')?></a>

                <?php if($item->approved == 0) : ?>
                    <a href="<?php echo site_url('admin/timby/approve/'.$item->id) ?>" title="<?php echo lang('timby:approve')?>" class="button"><?php echo lang('timby:approve')?></a>
                <?php else : ?>
                    <a href="<?php echo site_url('admin/timby/disapprove/'.$item->id) ?>" title="<?php echo lang('timby:disapprove')?>" class="button"><?php echo lang('timby:disapprove')?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>