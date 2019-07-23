<?php

namespace LofiFramework\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

class Meta_Premium_Terms
{
    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function get_instance()
    {
        if (!self::$_instance)
            self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * Getter functions for background color and text color term meta
     * 
     * @since 1.0.0
     * @access private
     * @return string Sanitized hex background color
     */
    public static function get_sanitized_bg_meta($term_id)
    {
        $value = get_term_meta($term_id, 'premium_color_picker_bg', true);
        $value = sanitize_hex_color($value);
        return $value;
    }

    public static function get_sanitized_text_meta($term_id)
    {
        $value = get_term_meta($term_id, 'premium_color_picker_text', true);
        $value = sanitize_hex_color($value);
        return $value;
    }

    /**
     * Register term meta for the premium taxonomy
     * 
     * @since 1.0.0
     * @access public
     */
    public function init_term_meta()
    {

        register_term_meta('premium_packages', 'premium_color_picker_bg', array(
            'sanitize_callback'     => 'sanitize_hex_color',
            'auth_callback'         => current_user_can('manage_lofi_terms'),
            'show_in_rest'          => true
        ));

        register_term_meta('premium_packages', 'premium_color_picker_text', array(
            'sanitize_callback'     => 'sanitize_hex_color',
            'auth_callback'         => current_user_can('manage_lofi_terms'),
            'show_in_rest'          => true
        ));
    }
    /**
     * Render the color pickers when in the 'add new' page for adding new terms
     * 
     * @since 1.0.0
     * @access public
     */
    public function bg_color_add_new_field()
    {

        wp_nonce_field(basename(__FILE__), 'lofi_term_color_bg_nonce');

        ?>

    <div class="form-field lofi-term-color-bg-wrap">
        <label for="lofi-term-color-bg"><?php _e('Background Color', 'lofi-framework'); ?></label>
        <input type="text" name="lofi-term-color-bg" id="lofi-term-color-bg" value="" class="lofi-color-field" data-default-color="#ffffff" />
    </div>
<?php
}

public function text_color_add_new_field()
{

    wp_nonce_field(basename(__FILE__), 'lofi_term_color_text_nonce');

    ?>

    <div class="form-field lofi-term-color-text-wrap">
        <label for="lofi-term-color-text"><?php _e('Text Color', 'lofi-framework'); ?></label>
        <input type="text" name="lofi-term-color-text" id="lofi-term-color-text" value="" class="lofi-color-field" data-default-color="#ffffff" />
    </div>

<?php
}

/**
 * Render the color pickers when in the edit page of the term
 * 
 * @since 1.0.0
 * @access public
 */
public function bg_color_edit_field($term)
{
    $default = '#ffffff';
    $color   = self::get_sanitized_bg_meta($term->term_id, true);

    if (!$color)
        $color = $default; ?>

    <tr class="form-field lofi-term-color-bg-wrap">
        <th scope="row">
            <label for="lofi-term-color-bg"><?php _e('Background Color', 'lofi-framework'); ?></label></th>
        <td>
            <?php wp_nonce_field(basename(__FILE__), 'lofi_term_color_bg_nonce'); ?>
            <input type="text" name="lofi-term-color-bg" id="lofi-term-color-bg" value="<?php echo esc_attr($color); ?>" class="lofi-color-field" data-default-color="<?php echo esc_attr($default); ?>" />
        </td>
    </tr>
<?php
}


public function text_color_edit_field($term)
{

    $default = '#ffffff';
    $color   = self::get_sanitized_text_meta($term->term_id, true);

    if (!$color)
        $color = $default; ?>

    <tr class="form-field lofi-term-color-text-wrap">
        <th scope="row">
            <label for="lofi-term-color-text"><?php _e('Text Color', 'lofi-framework'); ?></label></th>
        <td>
            <?php wp_nonce_field(basename(__FILE__), 'lofi_term_color_text_nonce'); ?>
            <input type="text" name="lofi-term-color-text" id="lofi-term-color-text" value="<?php echo esc_attr($color); ?>" class="lofi-color-field" data-default-color="<?php echo esc_attr($default); ?>" />
        </td>
    </tr>
<?php
}


/**
 * Save the background and text color metas
 * Compares old meta vs incoming meta before updating term meta
 * 
 * @since 1.0.0
 * @access public
 */
public function save_bg_color_term_meta($term_id)
{

    if (!isset($_POST['lofi_term_color_bg_nonce']) || !wp_verify_nonce($_POST['lofi_term_color_bg_nonce'], basename(__FILE__)))
        return;

    $old_color = self::get_sanitized_bg_meta($term_id);
    $new_color = isset($_POST['lofi-term-color-bg']) ? sanitize_hex_color($_POST['lofi-term-color-bg']) : '';

    if ($old_color && '' === $new_color)
        delete_term_meta($term_id, 'premium_color_picker_bg');

    else if ($old_color !== $new_color)
        update_term_meta($term_id, 'premium_color_picker_bg', $new_color);
}

public function save_text_color_term_meta($term_id)
{

    if (!isset($_POST['lofi_term_color_text_nonce']) || !wp_verify_nonce($_POST['lofi_term_color_text_nonce'], basename(__FILE__)))
        return;

    $old_color = self::get_sanitized_text_meta($term_id);
    $new_color = isset($_POST['lofi-term-color-text']) ? sanitize_hex_color($_POST['lofi-term-color-text']) : '';

    if ($old_color && '' === $new_color)
        delete_term_meta($term_id, 'premium_color_picker_text');

    else if ($old_color !== $new_color)
        update_term_meta($term_id, 'premium_color_picker_text', $new_color);
}

/**
 *  Plugin class constructor
 *
 * Register plugin action hooks and filters
 *
 * @since 1.0.0
 * @access public
 */
public function __construct()
{
    $this->init();
}

/**
 *  Init function that handles all hooks and filters
 * 
 * @since 1.0.0
 * @access public
 */
public function init()
{
    add_action('init', array($this, 'init_term_meta'));
    //Render the color pickers for bg color
    add_action('premium_packages_add_form_fields', array($this, 'bg_color_add_new_field'));
    add_action('premium_packages_edit_form_fields', array($this, 'bg_color_edit_field'));
    //Logic when saving bg color for both 'add new' and 'edit' 
    add_action('edit_premium_packages',   'save_bg_color_term_meta');
    //Render the color pickers for text color
    add_action('premium_packages_add_form_fields', array($this, 'text_color_add_new_field'));
    add_action('premium_packages_edit_form_fields', array($this, 'text_color_edit_field'));
    //Logic when saving bg color for both 'add new' and 'edit' 
    add_action('edit_premium_packages',   array($this, 'save_text_color_term_meta'));
    add_action('create_premium_packages', array($this, 'save_text_color_term_meta'));
}
};
Meta_Premium_Terms::get_instance();
