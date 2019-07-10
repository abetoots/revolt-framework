<div class="login-form-container">
    <?php if ($attributes['show_title']) : ?>
        <h2><?php _e('Sign In', 'lofi-framework'); ?></h2>
    <?php endif; ?>

    <!-- Show errors if there are any -->
    <?php if (count($attributes['errors']) > 0) : ?>
        <?php foreach ($attributes['errors'] as $error) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>


    <!-- Show logged out message if user just logged out -->
    <?php if ($attributes['logged_out']) : ?>
        <p class="login-info">
            <?php _e('You have signed out. Would you like to sign in again?', 'lofi-framework'); ?>
        </p>
    <?php endif; ?>

    <?php
    wp_login_form(
        array(
            'label_username' => __('Email', 'lofi-framework'),
            'label_log_in' => __('Sign In', 'lofi-framework'),
            'redirect'      => $attributes['redirect'],
        )
    );
    ?>

    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e('Forgot your password?', 'lofi-framework'); ?>
    </a>
</div>