<?php

namespace LofiFramework\Helpers;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

class Meta_JobPost
{

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
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
     * Callback to register meta boxes
     *
     * @since 1.0.0
     * @access public
     *
     * @uses add_meta_box();
     * @param post Object
     */
    public static function register_metabox_cb($post)
    {
        add_meta_box(
            'lofi-job-post-salary',
            '&dollar; Salary',
            array(MetaJobPost::get_instance(), 'render_salary_metabox'),
            $post->post_type,
            'advanced',
            'high'
        );

        add_meta_box(
            'lofi-job-post-company',
            'Company',
            array(MetaJobPost::get_instance(), 'render_employer_info_metabox'),
            $post->post_type,
            'advanced',
            'high'
        );
    }

    /**
     * Render the Salary Metabox
     * @uses update_post_meta
     * @param post Object
     */
    public static function render_salary_metabox($post)
    {
        //Get values, not the array by specifying 'true' in get post meta
        $isNegotiable = get_post_meta($post->ID, 'lofi-job-post-is-negotiable-field', true);
        $isDependsOnExperience = get_post_meta($post->ID, 'lofi-job-post-doe-field', true);
        $salaryMeta = get_post_meta($post->ID, 'lofi-job-post-salary-field', true);
        $salaryMetaOptional = get_post_meta($post->ID, 'lofi-job-post-salary-field-optional', true);
        $salaryValue = $salaryMeta ? esc_attr($salaryMeta) : '';
        $salaryValueOptional = $salaryMetaOptional ? esc_attr($salaryMetaOptional) : '';
        ?>
    <div class="lofi-salary-metaboxes">
        <div class="salary-container">
            <div class="salary-negotiable">
                <span>Negotiable?</span>
                <label class="format-switch" for="lofi-job-post-is-negotiable-field">
                    <?php wp_nonce_field('lofi_save_data_salary_negotiable', 'lofi-job-post-salary-negotiable-meta-nonce'); ?>
                    <input type="checkbox" id="lofi-job-post-is-negotiable-field" class="switch-input" name="lofi-job-post-is-negotiable-field" value="1" <?php checked($isNegotiable, 1); ?>>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="salary-doe">
                <span>Depends on Experience</span>
                <label class="format-switch" for="lofi-job-post-doe-field">
                    <?php wp_nonce_field('lofi_save_data_salary_doe', 'lofi-job-post-salary-doe-meta-nonce'); ?>
                    <input type="checkbox" id="lofi-job-post-doe-field" class="switch-input" name="lofi-job-post-doe-field" value="1" <?php checked($isDependsOnExperience, 1); ?>>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div id="lofi-salary-wrap">
            <div class="salary-container">
                <div class="field-1">
                    <p style="font-weight: bold;">Required</p>
                    <label class="lofi-job-salary" for="lofi-job-post-salary-field">
                        <?php wp_nonce_field('lofi_save_data_salary', 'lofi-job-post-salary-meta-nonce'); ?>
                        <span>&#36;</span><input type="number" id="lofi-job-post-salary-field" class="" name="lofi-job-post-salary-field" value="<?php echo $salaryValue ?>">
                    </label>
                </div>

                <div class="field-2">

                    <label class="lofi-job-salary" for="lofi-job-post-salary-field-optional">
                        <span class="salary-separator">&#8722;</span>
                        <span>&#36;</span>
                        <input type="number" id="lofi-job-post-salary-field-optional" class="" name="lofi-job-post-salary-field-optional" value="<?php echo $salaryValueOptional ?>">
                        <span style="font-style: italic;">(Optional) </span>
                    </label>
                </div>

            </div>
            <!-- end salary container -->
            <p style="font-style: italic;" class="hide-me-mobile">Optional Field represents the max salary if you want a
                salary range</p>
        </div>
        <!-- end salary wrap -->
        <!-- end salary metaboxes -->
    </div>
<?php
}

/**
 * Save Depends on Experience MetaData
 * @uses update_post_meta
 * @param post_id
 */
public function save_doe_toggle_data($post_id)
{
    if (!isset($_POST['lofi-job-post-salary-doe-meta-nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['lofi-job-post-salary-doe-meta-nonce'], 'lofi_save_data_salary_doe')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('publish_lofi_job_posts', $post_id)) {
        return;
    }

    $checkboxValue = (isset($_POST['lofi-job-post-doe-field']) && $_POST['lofi-job-post-doe-field'] == 1) ? 1 : 0;

    update_post_meta($post_id, 'lofi-job-post-doe-field', esc_attr($checkboxValue));
}

/**
 * Save Negotiable MetaData
 * @uses update_post_meta
 * @param post_id
 */
public function save_negotiable_toggle_data($post_id)
{
    if (!isset($_POST['lofi-job-post-salary-negotiable-meta-nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['lofi-job-post-salary-negotiable-meta-nonce'], 'lofi_save_data_salary_negotiable')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('publish_lofi_job_posts', $post_id)) {
        return;
    }


    $checkboxValue = (isset($_POST['lofi-job-post-is-negotiable-field']) && $_POST['lofi-job-post-is-negotiable-field'] == 1) ? 1 : 0;

    update_post_meta($post_id, 'lofi-job-post-is-negotiable-field', esc_attr($checkboxValue));
}

/**
 * * 1) Salary fields are saved ONLY if DOE is NOT toggled
 * 2) Salary field 1 is saved always
 * 3) Salary field 2 is optional
 * ! 4) If field 1 is greater than field 2, instantiate an error
 * @uses update_post_meta
 * @uses delete_post_meta
 * @uses WP_Error
 * @param post_id
 */
public function save_salary_data($post_id)
{
    if (!isset($_POST['lofi-job-post-salary-meta-nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['lofi-job-post-salary-meta-nonce'], 'lofi_save_data_salary')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('publish_lofi_job_posts', $post_id)) {
        return;
    }

    $salaryError = null; //To understand the purpose of this var, see end of this function

    $salaryValue = (isset($_POST['lofi-job-post-salary-field'])) ? absint($_POST['lofi-job-post-salary-field']) : '';
    $doeIsChecked = (isset($_POST['lofi-job-post-doe-field']) && $_POST['lofi-job-post-doe-field'] == 1) ? 1 : 0;

    //Update meta if DOE toggle is NOT checked
    if ($doeIsChecked == 0) {
        //First, check if optional value isset, compare the values
        if (isset($_POST['lofi-job-post-salary-field-optional'])) {
            $salaryValueOptional = absint($_POST['lofi-job-post-salary-field-optional']);

            //if field 1 is greater, instantiate an error
            if ($salaryValue > $salaryValueOptional && $salaryValueOptional) {

                //INSTANTIATE
                $salaryError = new WP_Error('salary-error', 'The post failed to save because the required salary field CANNOT be higher than the max salary range ');
            } else { //then update both values
                update_post_meta($post_id, 'lofi-job-post-salary-field', esc_attr($salaryValue));
                update_post_meta($post_id, 'lofi-job-post-salary-field-optional', esc_attr($salaryValueOptional));
            }
        } else { // not set?, just update first field
            update_post_meta($post_id, 'lofi-job-post-salary-field', esc_attr($salaryValue));
        }
    } elseif ($doeIsChecked == 1) { //if checked, delete post meta
        delete_post_meta($post_id, 'lofi-job-post-salary-field');
        delete_post_meta($post_id, 'lofi-job-post-salary-field-optional');
    }
    /**
     * @link https://www.sitepoint.com/displaying-errors-from-the-save_post-hook-in-wordpress/
     */
    //Run this if somewhere inside our code, $salaryError is assigned a value. see instantiate above
    // else, this won't run since initial value is 'null'
    if ($salaryError) {
        add_filter('redirect_post_location', function ($location) use ($salaryError) {
            return add_query_arg('salary-error', $salaryError->get_error_code(), $location);
        });
    }
}

/**
 * ! Render error notice for salary-error
 */
function render_salary_error()
{
    /**
     * @link https://www.sitepoint.com/displaying-errors-from-the-save_post-hook-in-wordpress/
     */
    if (array_key_exists('salary-error', $_GET)) : ?>
        <div class="error">
            <p>
                <?php
                switch ($_GET['salary-error']) {
                    case 'salary-error':
                        echo 'The post failed to save because the required salary field CANNOT be higher than the max salary range .';
                        break;
                }
                ?>
            </p>
        </div>
    <?php endif;
}

/**
 * Render the Salary Metabox
 * @uses update_post_meta
 * @param post Object
 */

public function render_employer_info_metabox($post)
{

    $companyMeta = get_post_meta($post->ID, 'lofi-job-post-company-field', true);
    $companyOption = esc_attr(get_option('company_name'));

    $employerPhotoMeta = get_post_meta($post->ID, 'lofi-job-post-employer-photo', true);
    $employerPhotoOption = esc_attr(get_option('employer_photo'));

    /**
     * If company meta is set, use it as value, otherwise , default to company name option 
     * set in Job Board Settings
     */

    $companyValue = ($companyMeta) ? esc_attr($companyMeta) : $companyOption;

    ?>
    <div class="lofi-company-metabox-wrap">
        <div class="company-name-container">
            <label class="lofi-job-company" for="lofi-job-post-company-field">
                <?php wp_nonce_field('lofi_save_data_company', 'lofi-job-post-company-meta-nonce'); ?>
                <input type="text" id="lofi-job-post-company-field" class="" name="lofi-job-post-company-field" value="<?php echo $companyValue ?>">
            </label>
        </div>
        <!-- end container -->
    </div>
    <!-- end wrap -->
    <p style="font-style: italic;">If not set, defaults to company name set in 'JobBoard Settings'</p>
<?php
}

/**
 * Save Negotiable MetaData
 * @uses update_post_meta
 * @param post_id
 */
public function save_employer_info_data($post_id)
{
    if (!isset($_POST['lofi-job-post-company-meta-nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['lofi-job-post-company-meta-nonce'], 'lofi_save_data_company')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('publish_lofi_job_posts', $post_id)) {
        return;
    }

    $companyOption = esc_attr(get_option('company_name'));
    $companyValue = (isset($_POST['lofi-job-post-company-field']) ?   $_POST['lofi-job-post-company-field']   : $companyOption);

    $companyValue = sanitize_text_field(wp_unslash($companyValue));

    update_post_meta($post_id, 'lofi-job-post-company-field', esc_attr($companyValue));
}

/**
 * Save Employer's Photo along with the post
 * @uses update_post_meta
 * @param post_id
 */

// public function save_persistent_data_employer_photo($post_id)
// {
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     if (!current_user_can('publish_lofi_job_posts', $post_id)) {
//         return;
//     }
// }

/**
 * Plugin class constructor
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
 * Init function that handles all hooks and filters
 *
 * @since 1.0.0
 * @access public
 */
public function init()
{
    //Salary Metabox
    add_action('save_post_lofi-job-post', array($this, 'save_doe_toggle_data'));
    add_action('save_post_lofi-job-post', array($this, 'save_negotiable_toggle_data'));
    add_action('save_post_lofi-job-post', array($this, 'save_salary_data'));
    add_action('admin_notices', array($this, 'render_salary_error'));
    //Company Metabox
    add_action('save_post_lofi-job-post', array($this, 'save_employer_info_data'));
    // add_action('save_post_lofi-job-post', array($this, 'save_persistent_data_employer_photo'));
}
}
Meta_JobPost::get_instance();
