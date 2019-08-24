<?php

namespace Revolt_Framework\Core\Helpers\ACF;


if (!defined('ABSPATH')) {
    exit;
}

add_filter('acf/location/rule_types', __NAMESPACE__ . '\add_location_rule_types');
function add_location_rule_types($choices)
{

    $choices['User']['capabilities'] = 'Capabilities';
    $choices['User']['profile_form'] = 'Profile';

    return $choices;
}

add_filter('acf/location/rule_values/capabilities', __NAMESPACE__ . '\populate_rule_type_capabilities');
function populate_rule_type_capabilities($choices)
{
    $caps  = get_role('administrator')->capabilities;
    if ($caps) {

        foreach ($caps as $key => $cap) {

            $choices[$key] = $key;
        }
    }

    return $choices;
}

add_filter('acf/location/rule_match/capabilities', __NAMESPACE__ . '\acf_location_rule_match_user', 10, 4);
function acf_location_rule_match_user($match, $rule, $options, $field_group)
{
    // error_log(print_r($options, 1));
    if ($rule['operator'] == "==") {
        if (array_key_exists('user_id', $options)) {
            $match = current_user_can($rule['value'], $options['user_id']);
        } elseif (array_key_exists('post_id', $options)) {
            $match = current_user_can($rule['value'], $options['post_id']);
        }
    } elseif ($rule['operator'] == "!=") {
        if (array_key_exists('user_id', $options)) {
            $match = current_user_can($rule['value'], $options['user_id']);
        } elseif (array_key_exists('post_id', $options)) {
            $match = current_user_can($rule['value'], $options['post_id']);
        }
    }
    return $match;
}

add_filter('acf/location/rule_values/profile_form', __NAMESPACE__ . '\add_profile_choices');
function add_profile_choices($choices)
{
    $choices['add'] = 'Add New';
    $choices['edit'] = 'Your Profile';
    return $choices;
}

add_filter('acf/location/rule_match/profile_form', __NAMESPACE__ . '\match_profile_rule', 10, 4);
function match_profile_rule($match, $rule, $options, $field_group)
{

    if ($rule['operator'] == "==") {
        if (array_key_exists('user_form', $options)) {
            $match = $options['user_form'] === $rule['value'];
        }
    } elseif ($rule['operator'] == "!=") {
        if (array_key_exists('user_form', $options)) {
            $match = $options['user_form'] !== $rule['value'];
        }
    }
    return $match;
}

/**
 * Local JSON save point
 */

add_filter('acf/settings/save_json', __NAMESPACE__ . '\my_acf_json_save_point');
function my_acf_json_save_point($path)
{
    // update path
    $path = REVOLT_FRAMEWORK_DIR . '/inc/libraries/acf/acf-json';

    // return
    return $path;
}


/**
 * Local JSON load point
 */
add_filter('acf/settings/load_json', __NAMESPACE__ . '\my_acf_json_load_point');
function my_acf_json_load_point($paths)
{
    // remove original path (optional)
    unset($paths[0]);
    // append path
    $paths[] = REVOLT_FRAMEWORK_DIR . '/inc/libraries/acf/acf-json';
    // return
    return $paths;
}
