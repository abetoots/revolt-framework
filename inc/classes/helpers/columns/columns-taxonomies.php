<?php

namespace LofiFramework\Helpers\Columns;

use LofiFramework\Helpers\Meta_Premium_Terms;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class Columns_Taxonomies
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
     * Register columns for premium package taxonomy
     * @return Array $new_columns
     */
    public function register_columns_premium_package($columns)
    {
        unset($columns['description']);
        unset($columns['slug']);

        /**
         * Arrange our columns while we're at it
         * @link https://www.isitwp.com/change-wordpress-admin-post-columns-order/
         */
        $before = 'posts'; //before count
        $new_columns = array();
        foreach ($columns as $key => $value) {
            if ($key == $before) {
                $new_columns['bg-color'] = __('BG Color');
                $new_columns['text-color'] = __('Text Color');
                $new_columns['output'] = __('Output<br>(front-end font <br> may vary)');
            }
            $new_columns[$key] = $value;
        }

        return $new_columns;
    }

    /**
     * Populate Premium Package custom columns
     */
    public function populate_custom_columns_premium_package($out,  $column, $term_id)
    {

        //POPULATE BG COLOR COLUMN
        if ($column === 'bg-color') {

            $color = Meta_Premium_Terms::get_sanitized_bg_meta($term_id);

            if (!$color) {
                $color = '#ffffff';
            }
            $out =  '<div class="premium-color-block" style="background:' . $color . ';">&nbsp;</div>';

            //POPULATE TEXT COLOR COLUMN
        } elseif ($column === 'text-color') {

            $color = Meta_Premium_Terms::get_sanitized_text_meta($term_id);


            if (!$color) {
                $color = '#ffffff';
            }
            $out = '<div class="premium-color-block" style="background:' . $color . ';">&nbsp;</div>';

            //POPULATE OUTPUT COLUMN
        } elseif ($column === 'output') {

            $bgColor = Meta_Premium_Terms::get_sanitized_bg_meta($term_id);
            $textColor = Meta_Premium_Terms::get_sanitized_text_meta($term_id);
            $term = get_term($term_id, 'premium_packages');
            $name = $term->name;

            $default  = '#ffffff';

            if (!$bgColor) {
                $bgColor = $default;
            }

            if (!$textColor) {
                $textColor = $default;
            }

            // Add class lofi-initial-terms to the initial terms
            //purpose of this class is display:flex to make icons inline with the term name
            switch ($name) {
                case 'Featured':
                case 'Urgent':
                case 'With Benefits':
                case 'Verified':
                    $initialTerms = 'lofi-initial-terms';
                    break;

                default:
                    $initialTerms = '';
            }

            //Add the appropriate span classes for dashicon purposes to the initial terms
            switch ($name) {

                case 'Featured':
                    $icon = '<span class ="lofi-featured lofi-output-icon"></span>';
                    break;

                case 'Urgent':
                    $icon = '<span class ="lofi-urgent lofi-output-icon"></span>';
                    break;

                case 'With Benefits':
                    $icon = '<span class ="lofi-with-benefits lofi-output-icon"></span>';
                    break;

                case 'Verified':
                    $icon = '<span class ="lofi-verified lofi-output-icon"></span>';
                    break;

                default:
                    $icon = '';
            }


            $out =  '<div class="lofi-premium-output  output-' . $term_id . ' ' . $initialTerms . '" style="background:' . $bgColor . '; color: ' . $textColor . '">
                    ' . $icon . ' 
                    <span>' . $name . '</span>
                </div>';
        }
        return $out;
    }

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
        //Premium Pacakage Columns
        add_filter('manage_edit-premium_packages_columns', array($this, 'register_columns_premium_package'));
        add_filter('manage_premium_packages_custom_column', array($this, 'populate_custom_columns_premium_package'), 10, 3);
    }
}
Columns_Taxonomies::get_instance();
