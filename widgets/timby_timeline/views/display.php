<script type="text/javascript">
    var min_timeline_day = <?php echo date('d', $min_date_timestamp); ?>;
    var min_timeline_month = <?php echo date('m', $min_date_timestamp); ?>;
    var min_timeline_year = <?php echo date('Y', $min_date_timestamp); ?>;
    var timeline_step_interval = <?php echo $step_interval; ?>;

    function change_timelines()
    {
        control_value = document.getElementById("timby_timeline_control").value;
        window.Timby.current_timeline_value = parseInt(control_value);
        window.Timby.filterBySector(window.Timby.current_sector);
    }
</script>

<div class="timeslider" id="timeslider">
    <p><input id="timby_timeline_control" type="range" value="1" min="1" max="<?php echo $number_of_ticks; ?>" step="1" onchange="change_timelines();"/></p>
</div>