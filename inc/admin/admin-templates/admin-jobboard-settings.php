<?php settings_errors(); ?>
<form method="post" action="options.php" class='lofi-job-board-form'>
    <?php settings_fields('lofi-jobboard-settings'); ?>
    <?php do_settings_sections('lofi_jobboard'); ?>
    <?php submit_button(); ?>
</form>