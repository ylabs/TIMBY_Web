<button class="close">x</button>
<?php if($report) : ?>
    <div class="wrapper">
        <div class="context subheader">
            <h1 class="title"><?php echo $report->title; ?></h1>
            <p><span class="category">date</span> <?php echo date("jS M Y", strtotime($report->report_date)); ?></p>
            <p><span class="category">entities</span> <?php echo count($entities) > 0 ? implode(", ", $entities) : "N/A"; ?> </p>
            <p>
                <span class="category">tags</span>
                <span class="tags"><?php echo $category == false ? "N/A" : $category->category; ?></span>
            </p>
        </div>

        <h3>Description</h3>
        <div class="context content" id="report_content">
            <?php echo $report_post; ?>
        </div>
    </div>
<?php else : ?>
<?php endif; ?>