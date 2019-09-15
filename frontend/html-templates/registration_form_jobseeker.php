<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="Registration__container">

    <?php if (count($attributes['errors']) > 0) :
        foreach ($attributes['errors'] as $error) : ?>
            <p class="Registration__error"> <span role="img" aria-label="registration-error">⚠️</span><?php echo $error; ?></p>
    <?php endforeach;
    endif; ?>

    <h2 class="Registration__heading"><?php _e($attributes['title'], 'revolt-framework');  ?></h2>

    <?php $nonce = wp_create_nonce('register_jobseeker_form_nonce'); ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" autocomplete="off">

        <input type="hidden" name="action" value="revolt_register_jobseeker">
        <input type="hidden" name="register_jobseeker_nonce" value="<?php echo $nonce ?>" />
        <div class="Registration__username">
            <label for="username"><?php _e('Username', 'revolt-framework'); ?> <strong>*</strong></label>
            <div>
                <input type="text" name="username" value="" class="Registration__input" placeholder="Username" autocomplete="off" required>
            </div>
        </div>

        <div class="Registration__email">
            <label for="js_email"><?php _e('Email', 'revolt-framework'); ?> <strong>*</strong></label>
            <div>
                <input type="email" name="email" value="" class="Registration__input" placeholder="Your Email" autocomplete="off" required>
            </div>
        </div>

        <div class="Registration__password">
            <label for="password"><?php _e('Password', 'revolt-framework'); ?> <strong>*</strong></label>
            <div>
                <input type="password" name="password" value="" class="Registration__input" class="Your Password" autocomplete="off" required>
            </div>
        </div>

        <?php if ($attributes['recaptcha_site_key']) : ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $attributes['recaptcha_site_key']; ?>"></div>
            </div>
        <?php endif; ?>

        <div class="Registration__submit">
            <button type="submit" class="Registration__submitBtn" <?php disabled($attributes['disabled'], true) ?>>
                <?php esc_html_e($attributes['button_text'], 'revolt-framework'); ?>
            </button>
        </div>
    </form>
</div>