<?php

namespace Revolt_Framework\Core\Helpers\API;

use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * Adds the taxonomy term NAMES to the job post
 */
add_action('rest_api_init', __NAMESPACE__ . '\add_taxonomy_terms');
function add_taxonomy_terms()
{
    $taxonomies = get_object_taxonomies('revolt-job-post');
    foreach ($taxonomies as $taxonomy) {
        //dynamically calls register_rest_field for each taxonomy
        dynamic_register_term_fields('revolt-job-post', $taxonomy);
    }
}
//Instead of writing each register rest field, use this instead
function dynamic_register_term_fields($post_type, $taxonomy)
{
    return register_rest_field($post_type, $taxonomy . "_data", array(
        'get_callback'  => function ($obj_type, $attribute) {
            /**
             * This anonymous callback function receives 2 params, the post object
             * and the name/attribute of the registered field($taxonomy . "_data")
             * 
             * We strip the "_terms" so we can dynamically use it in get_the_terms()
             */
            $taxonomy_type =  str_replace('_data', '', $attribute);
            //Returns an array of objects
            $terms = get_the_terms($obj_type['id'], $taxonomy_type);
            //Initialize an array to be returned
            $return_data = array();
            //extract the term name
            if (is_array($terms)) {
                foreach ($terms as $term) {
                    // push the term name
                    $return_data = $term->name;
                }
            }

            return $return_data;
        }
    ));
}

/**
 * Register rest fields for our job post
 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
 */
add_action('rest_api_init', __NAMESPACE__ . '\create_api_job_post_meta_field');

function create_api_job_post_meta_field()
{

    register_rest_field('revolt-job-post', 'job_applicants', array(
        'get_callback' => function ($obj_type) {
            // delete_post_meta($obj_type['id'], 'applicants');
            $return_data = get_post_meta($obj_type['id'], 'applicants', true);
            return $return_data;
        },
        'update_callback' => function ($val, $obj_type, $fieldName) {
            //Defensive checks
            $user = get_userdata(absint($val));
            //checks if user exists
            if ($user === false) {
                return new WP_Error(
                    'rest_cannot_apply',
                    __('Sorry, are you sure you exist?'),
                    array('status' => 400)
                );
            }

            //checks if user is a jobseeker
            $roles = $user->roles;
            if (!in_array('jobseeker', $roles)) {
                return new WP_Error(
                    'rest_cannot_apply',
                    __('Sorry, you don\'t seem to be an approriate user'),
                    array('status' => 400)
                );
            }
            //if we reach this, get the array of applicants and append the user's ID
            $id = $obj_type->ID;
            $meta = get_post_meta($id, 'applicants', true);
            if (!is_array($meta)) {
                $meta = array();
            }
            $val = absint($val);
            $meta[] = $val;
            update_post_meta($id, 'applicants', $meta);
        }
    ));

    register_rest_field(
        'revolt-job-post',
        'job-post-meta-fields',
        array(
            'get_callback' => __NAMESPACE__ . '\get_job_post_meta_for_api',
            'schema' => null,
        )
    );
}

/**
 * Registers rest fields for users
 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
 */
add_action('rest_api_init', __NAMESPACE__ . '\add_user_acf_fields_to_rest');
function add_user_acf_fields_to_rest()
{
    register_rest_field('user', 'revolt_settings', array(
        'get_callback'  => function ($obj_type) {
            $id = $obj_type['id'];
            $user_id = 'user_' . $id;
            $return_data = get_fields($user_id);
            return $return_data;
        },
        'update_callback' => function ($value, $obj, $fieldName) {
            $id = $obj->ID;
            $user_id = 'user_' . $id;
            foreach ($value as $selector => $val) {
                if ($selector === 'revolt_js_bookmarks') {
                    //defensive absint
                    $post = get_post(absint($val));
                    if ($post === null) {
                        return new WP_Error(
                            'rest_cannot_bookmark',
                            __('Sorry, we can\'t seem to find that job post.'),
                            array('status' => 400)
                        );
                    }

                    //if we reach this, get the array of applicants and append the user's ID
                    $value = get_field($selector, $user_id);
                    if (!is_array($value)) {
                        $value = array();
                    }
                    //add the applicant's id to existing array of applicants
                    $value[] = absint($val);

                    update_field($selector, $value, $user_id);
                } else {
                    //defensive sanitize
                    $val = sanitize_meta($selector, $val, 'user');
                    update_field($selector, $val, $user_id);
                }
            }
        }
    ));

    register_rest_field('user', 'revolt_user_role', array(
        'get_callback'  => function ($obj_type) {
            $userdata = get_userdata($obj_type['id']);
            if (in_array('administrator', $userdata->roles)) {
                return $return_data = 'administrator';
            } elseif (in_array('employer', $userdata->roles)) {
                return $return_data = 'employer';
            } else if (in_array('jobseeker', $userdata->roles)) {
                return $return_data = 'jobseeker';
            }
            return $return_data;
        }
    ));
}



/**
 * Filters out revolt_employer_settings for non-employers and non-admin users
 */
add_filter('rest_prepare_user', __NAMESPACE__ . '\remove_some_fields', 10, 3);
function remove_some_fields($response, $user, $request)
{
    $data = $response->get_data();
    if (!$user->allcaps["read_revolt_job_post"]) {
        $data = $response->get_data();
        //for non-employers and non-admin
        unset($data['revolt_settings']);
        unset($data['revolt_user_role']);

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
    if (in_array('administrator', $user->roles)) {
        $data['role'] = 'administrator';
    } elseif (in_array('employer', $user->roles)) {
        $data['role'] = 'employer';
    } else if (in_array('jobseeker', $user->roles)) {
        $data['role'] = 'jobseeker';
    }
    return $data;
}

//HELPERS
function get_job_post_meta_for_api($object)
{
    //get the id of the post object array
    $post_id = $object['id'];
    $metas = get_post_meta($post_id);
    $return = array();
    foreach ($metas as $key => $val) {
        //ignore 'private' meta
        if (substr($key, 0, 1) == '_') {
            continue;
        } else {
            $return[$key] = $val;
        }
    }
    return $return;
}
