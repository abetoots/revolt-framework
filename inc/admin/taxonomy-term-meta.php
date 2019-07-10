<?php



/*
                        ========================================================
                        =        TERM META BACKGROUND COLOR FOR PREMIUM JOBS   =
                        ========================================================
*/

function lofi_register_meta() {

    register_term_meta( 'premium_packages', 'premium_color_picker_bg', array(
        'sanitize_callback'     => 'lofi_sanitize_hex'
    ) );

    register_term_meta( 'premium_packages', 'premium_color_picker_text', array(
        'sanitize_callback'     => 'lofi_sanitize_hex'
    ) );
}

add_action( 'init', 'lofi_register_meta' );

//SANITIZE DATA CB

function lofi_sanitize_hex( $color ) {

    $color = sanitize_hex_color( $color );
    return $color;
}

//GETTER FUNCTION , CALLS SANITIZE DATA CB

function lofi_get_term_meta_color_bg( $term_id ) {
    $value = get_term_meta( $term_id, 'premium_color_picker_bg', true );
    $value = lofi_sanitize_hex($value);
    return $value;
}


// FOR THE 'ADD NEW'
function lofi_add_field_term_meta_color_bg() {

    wp_nonce_field( basename( __FILE__ ), 'lofi_term_color_bg_nonce' ); 
    
    ?>

    <div class="form-field lofi-term-color-bg-wrap">
        <label for="lofi-term-color-bg"><?php _e( 'Background Color', 'lofi-framework' ); ?></label>
        <input type="text" name="lofi-term-color-bg" id="lofi-term-color-bg" value="" class="lofi-color-field" data-default-color="#ffffff" />
    </div>
    <?php 
}
add_action( 'premium_packages_add_form_fields', 'lofi_add_field_term_meta_color_bg' );



//FOR EDIT
function lofi_edit_field_term_meta_color_bg( $term ) {
    $default = '#ffffff';
    $color   = lofi_get_term_meta_color_bg( $term->term_id, true );

    if ( ! $color )
        $color = $default; ?>

    <tr class="form-field lofi-term-color-bg-wrap">
        <th scope="row">
            <label for="lofi-term-color-bg"><?php _e( 'Background Color', 'lofi-framework' ); ?></label></th>
        <td>
            <?php wp_nonce_field( basename( __FILE__ ), 'lofi_term_color_bg_nonce' ); ?>
            <input type="text" name="lofi-term-color-bg" id="lofi-term-color-bg" value="<?php echo esc_attr( $color ); ?>" class="lofi-color-field" data-default-color="<?php echo esc_attr( $default ); ?>" />
        </td>
    </tr>
<?php
}

add_action( 'premium_packages_edit_form_fields', 'lofi_edit_field_term_meta_color_bg' );

//WHEN SAVING
function lofi_save_term_color_bg( $term_id ) {

    if ( ! isset( $_POST['lofi_term_color_bg_nonce'] ) || ! wp_verify_nonce( $_POST['lofi_term_color_bg_nonce'], basename( __FILE__ ) ) )
        return;

    $old_color = lofi_get_term_meta_color_bg( $term_id );
    $new_color = isset( $_POST['lofi-term-color-bg'] ) ? lofi_sanitize_hex( $_POST['lofi-term-color-bg'] ) : '';

    if ( $old_color && '' === $new_color )
        delete_term_meta( $term_id, 'premium_color_picker_bg' );

    else if ( $old_color !== $new_color )
        update_term_meta( $term_id, 'premium_color_picker_bg', $new_color );
}
add_action( 'edit_premium_packages',   'lofi_save_term_color_bg' );
add_action( 'create_premium_packages', 'lofi_save_term_color_bg' );

/*
                        ====================================
                        =       TEXT COLOR TERM META       =
                        ====================================
*/



//GETTER FUNCTION , CALLS SANITIZE DATA CB

function lofi_get_term_meta_color_text( $term_id ) {
    $value = get_term_meta( $term_id, 'premium_color_picker_text', true );
    $value = lofi_sanitize_hex($value);
    return $value;
}


// FOR THE 'ADD NEW'
function lofi_add_field_term_meta_color_text() {

    wp_nonce_field( basename( __FILE__ ), 'lofi_term_color_text_nonce' ); 
    
    ?>

    <div class="form-field lofi-term-color-text-wrap">
        <label for="lofi-term-color-text"><?php _e( 'Text Color', 'lofi-framework' ); ?></label>
        <input type="text" name="lofi-term-color-text" id="lofi-term-color-text" value="" class="lofi-color-field" data-default-color="#ffffff" />
    </div>

    <?php 
}
add_action( 'premium_packages_add_form_fields', 'lofi_add_field_term_meta_color_text' );



//FOR EDIT
function lofi_edit_field_term_meta_color_text( $term ) {

    $default = '#ffffff';
    $color   = lofi_get_term_meta_color_text( $term->term_id, true );

    if ( ! $color )
        $color = $default; ?>
        
    <tr class="form-field lofi-term-color-text-wrap">
        <th scope="row">
            <label for="lofi-term-color-text"><?php _e( 'Text Color', 'lofi-framework' ); ?></label></th>
        <td>
            <?php wp_nonce_field( basename( __FILE__ ), 'lofi_term_color_text_nonce' ); ?>
            <input type="text" name="lofi-term-color-text" id="lofi-term-color-text" value="<?php echo esc_attr( $color ); ?>" class="lofi-color-field" data-default-color="<?php echo esc_attr( $default ); ?>" />
        </td>
    </tr>
    <?php
}
add_action( 'premium_packages_edit_form_fields', 'lofi_edit_field_term_meta_color_text' );


//WHEN SAVING
function lofi_save_term_color_text( $term_id ) {

    if ( ! isset( $_POST['lofi_term_color_text_nonce'] ) || ! wp_verify_nonce( $_POST['lofi_term_color_text_nonce'], basename( __FILE__ ) ) )
        return;

    $old_color = lofi_get_term_meta_color_text( $term_id );
    $new_color = isset( $_POST['lofi-term-color-text'] ) ? lofi_sanitize_hex( $_POST['lofi-term-color-text'] ) : '';

    if ( $old_color && '' === $new_color )
        delete_term_meta( $term_id, 'premium_color_picker_text' );

    else if ( $old_color !== $new_color )
        update_term_meta( $term_id, 'premium_color_picker_text', $new_color );
}
add_action( 'edit_premium_packages',   'lofi_save_term_color_text' );
add_action( 'create_premium_packages', 'lofi_save_term_color_text' );