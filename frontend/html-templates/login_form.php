<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="Login__container">

    <!-- Show errors if there are any -->
    <?php if (count($attributes['errors']) > 0) : ?>
        <?php foreach ($attributes['errors'] as $error) : ?>
            <p class="Login__error">
                <?php echo esc_html($error); ?>
            </p>
    <?php endforeach;
    endif; ?>

    <?php if (isset($attributes['new_user'])) : ?>
        <p class="Login__newUser"><?php _e('Registration successful! 🔑', 'revolt-framework'); ?></p>
    <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ($attributes['logged_out']) : ?>
        <p class="Login__info -loggedOut">
            <?php _e('You have signed out. <br> Would you like to sign in again?', 'revolt-framework'); ?>
        </p>
    <?php endif; ?>

    <h2 class="Login__title"><?php _e($attributes['title'], 'revolt-framework') ?></h2>

    <form id="login-form" method="post" action="<?php echo esc_url(wp_login_url()); ?>" autocomplete="on">
        <div class="Login__username">
            <label for="user_login"><?php _e('Email or Username', 'revolt-framework'); ?></label>
            <div>
                <input type="text" name="log" id="user_login" class="Login__input" placeholder="Email or Username">
            </div>

        </div>
        <div class="Login__password">
            <label for="user_pass"><?php _e('Password', 'revolt-framework'); ?></label>
            <div>
                <input type="password" name="pwd" id="user_pass" class="Login__input" placeholder="Password">
            </div>
        </div>
        <div class="Login__submit">
            <button type="submit" class="Login__submitBtn"><?php esc_html_e($attributes['button_text'], 'revolt-framework'); ?></button>
        </div>
    </form>

    <!-- //TODO maybe implement our own lost password user flow -->
    <a class="forgot-password" href="<?php echo esc_url(wp_lostpassword_url()); ?>">
        <?php _e('Forgot your password?', 'revolt-framework'); ?>
    </a>
</div>