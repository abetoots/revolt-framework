<?php settings_errors(); ?>
<form method="post" action="options.php" class='revolt-job-board-form'>
    <?php settings_fields('revolt-jobboard-settings'); ?>
    <?php do_settings_sections('revolt_jobboard'); ?>
    <?php submit_button(); ?>
</form>