<?php

namespace LofiFramework\Helpers\Utilities;

use LofiFramework;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * Adds the taxonomy terms to the job post CPT rest api route '/wp-json/wp/v2/lofi-job-post'.
 */
add_action('rest_api_init', __NAMESPACE__ . '\add_taxonomy_terms');
function add_taxonomy_terms()
{
    $taxonomies = get_object_taxonomies('lofi-job-post');
    foreach ($taxonomies as $taxonomy) {
        //dynamically calls register_rest_field for each taxonomy
        register_term_rest_fields_dynamic('lofi-job-post', $taxonomy);
    }
}
//Instead of writing each register rest field, use this instead
function register_term_rest_fields_dynamic($post_type, $taxonomy)
{
    return register_rest_field($post_type, $taxonomy . "_data", array(
        'get_callback'  => function ($obj_arr, $attribute) {
            /**
             * This anonymous callback function receives 2 params, the post object
             * and the name/attribute of the registered field which is $taxonomy."_terms"
             * 
             * We strip the "_terms" so we can dynamically use it in get_the_terms()
             */
            $taxonomy_type =  str_replace('_data', '', $attribute);
            //Returns an array of objects
            $terms = get_the_terms($obj_arr['id'], $taxonomy_type);
            $return_data = array();
            //extract the term name
            foreach ($terms as $term) {
                //fetch meta data if any, useful for meta attached to premium_package taxonomy terms
                $termMeta = get_term_meta($term->term_id);
                //if empty, just push the term name
                if (!$termMeta) {
                    $return_data = $term->name;
                } else {
                    $return_data[$term->name] = $termMeta;
                }
            }
            return $return_data;
        }
    ));
}

/**
 * Adds the taxonomy terms to the job post CPT rest api route '/wp-json/wp/v2/lofi-job-post'.
 */
add_action('rest_api_init', __NAMESPACE__ . '\add_user_meta_to_rest');

//Instead of writing each register rest field, use this instead
function add_user_meta_to_rest()
{
    register_rest_field('user', 'lofi_employer_settings', array(
        'get_callback'  => function ($obj_arr) {
            // $meta_keys = LofiFramework\Core\CustomPostType::$meta_keys;
            $id = $obj_arr['id'];
            $user_id = 'user_' . $id;
            $return_data = get_fields($user_id);
            return $return_data;
        },
        'update_callback' => function ($value, $obj_arr, $fieldName) {
            return update_user_meta($obj_arr['id'], $fieldName, $value);
        }
    ));
}




add_filter('rest_prepare_user', __NAMESPACE__ . '\remove_some_fields', 10, 3);
function remove_some_fields($response, $user, $request)
{
    if (!in_array('administrator', $user->roles, true) && !in_array('jobseeker', $user->roles, true) && !in_array('employer', $user->roles, true)) {

        $data = $response->get_data();

        unset($data['lofi_employer_settings']);

        $response->set_data($data);
    }

    return $response;
}

add_filter('rest_user_query', __NAMESPACE__ . '\remove_has_published_posts_from_api_user_query', 10, 2);
/**
 * Removes `has_published_posts` from the query args so even users who have not
 * published content are returned by the request.
 *
 * @see https://developer.wordpress.org/reference/classes/wp_user_query/
 *
 * @param array           $prepared_args Array of arguments for WP_User_Query.
 * @param WP_REST_Request $request       The current request.
 *
 * @return array
 */
function remove_has_published_posts_from_api_user_query($prepared_args, $request)
{
    unset($prepared_args['has_published_posts']);

    return $prepared_args;
}

add_filter('jwt_auth_token_before_dispatch', __NAMESPACE__ . '\add_user_slug_to_response', 10, 2);
/**
 * Adds the username to our token response
 */
function add_user_slug_to_response($data, $user)
{
    $data['username'] = $user->user_login;
    return $data;
}


add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }
    return $result;
});
