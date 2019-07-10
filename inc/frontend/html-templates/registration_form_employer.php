<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>


<div id="register-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
    <h3><?php _e($attributes['title'], 'lofi-framework');  ?></h3>
    <?php endif; ?>


    <?php if ( !$attributes['disabled'] ) : // no need for error display when viewing in elementor  ?>
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
    <?php foreach ( $attributes['errors'] as $error ) : ?>
    <p>
        <?php echo $error; ?>
    </p>
    <?php endforeach; ?>
    <?php endif; endif;?>



    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" autocomplete="on">

        <input type="hidden" name="action" value="lofi_registration_form_action">
        <?php wp_nonce_field( 'lofi_registration_form_action', 'lofi-registration-nonce' ); ?>
        <div class="form-row">
            <label for="username"><?php _e( 'Username', 'lofi-framework' ); ?> <strong>*</strong></label>
            <input type="text" name="username" value="">
        </div>

        <div class="form-row">
            <label for="email"><?php _e( 'Email', 'lofi-framework' ); ?> <strong>*</strong></label>
            <input type="email;" name="email" id="email" value="">
        </div>

        <div class="form-row">
            <label for="password"><?php _e( 'Password', 'lofi-framework' ); ?> <strong>*</strong></label>
            <input type="password" name="password" id="password" value="">
        </div>

        <?php if ( $attributes['recaptcha_site_key'] ) : ?>
        <div class="recaptcha-container">
            <div class="g-recaptcha" data-sitekey="<?php echo $attributes['recaptcha_site_key']; ?>"></div>
            </i>
            <?php endif; ?>

            <p class="signup-submit">
                <input type="submit" name="submit_lofi_employer" class="register-button"
                    value="<?php echo esc_attr__( $attributes['button_text'] ); ?>" <?php if($attributes['disabled']):
                       ?> disabled <?php
                   endif;
                   ?> style="cursor:pointer;" />
            </p>
    </form>
</div>