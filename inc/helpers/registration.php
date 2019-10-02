<?php

namespace Revolt_Framework\Inc\Helpers\Registration;

use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validates and then completes the new employer signup process if all went well.
 *
 * @param string $username          The new employer's email address
 * @param string $email             The new employer's username
 * @param string $password          The new employer's password
 *
 * @return int|WP_Error         The id of the user that was created, or error if failed.
 */
function validate_and_register_new_user($username, $email, $password, $role)
{
    $errors = array();

    //Username, password and email are required and must not be left out
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = 'empty_field';
    }

    //Make sure the number of username characters is not less than 4
    if (4 > strlen($username)) {
        $errors[] = 'username_length';
    }

    //Check if the username is already registered
    if (username_exists($username)) {
        $errors[] = 'username_exists';
    }

    //Make sure the username is valid
    if (!validate_username($username)) {
        $errors[] = 'invalid_username_register';
    }

    //Ensure the password entered by users is not less than 5 characters
    if (5 > strlen($password)) {
        $errors[] = 'password_length';
    }

    //Check if valid email
    if (!is_email($email)) {
        $errors[] = 'email';
    }

    //Check if email is already registered
    if (email_exists($email)) {
        $errors[] = 'email_exists';
    }

    //return errors if any
    if (!empty($errors)) {
        $wp_error = new WP_Error();
        foreach ($errors as $error) {
            $wp_error->add($error, get_error_message($error));
        }
        return $wp_error;
    }

    //If we reach here, Sanitize before inserting user data
    $email = sanitize_email($email);
    $username = sanitize_text_field($username);

    $user_data = array(
        'user_login'    => $username,
        'user_email'    => $email,
        'user_pass'     => $password,
        'user_nicename' => $username,
        'role'          => $role,
    );

    $user_id = wp_insert_user($user_data);
    //update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
    //wp_new_user_notification( $user_id, $password );

    return $user_id;
}

/**
 * Instead of manually inputting an error message at a given WP_Error instance,
 * we outsource and handle it all in this function.
 * 
 * Finds and returns a matching error message for the given error code.
 *
 * @param string $error_code    The error code to look up.
 *
 * @return string               An error message.
 */
function get_error_message($error_code)
{
    switch ($error_code) {
            //Registration Error Codes
        case 'username_length':
            return __('Username is too short- that\'s what she said', 'revolt-framework');

        case 'username_exists':
            return __('Username already exists', 'revolt-framework');

        case 'invalid_username_register':
            return __(
                'Somehow that username is invalid. Maybe use a different one?',
                'revolt-framework'
            );

        case 'password_length':
            return __('Password is too short- that\'s what she said', 'revolt-framework');

        case 'email':
            return __('The email address you entered is not valid.', 'revolt-framework');

        case 'email_exists':
            return __('An account exists with this email address.', 'revolt-framework');

        case 'closed':
            return __('Registering new users is currently not allowed.', 'revolt-framework');
        case 'disabled':
            return __('Job Board Registration is currently not allowed.', 'revolt-framework');
        case 'captcha':
            return __('The Google reCAPTCHA check failed. Are you a robot?', 'revolt-framework');


            //Login Error Codes
        case 'invalid_username':
            return __(
                "Invalid username/email",
                'revolt-framework'
            );

        case 'incorrect_password':
            $err = __(
                "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                'revolt-framework'
            );
            return sprintf($err, wp_lostpassword_url());

            //Neutral Error Codes
        case 'empty_field':
            return __('You forgot some fields though', 'revolt-framework');

        default:
            break;
    }

    return __('An unknown error occurred. Please try again later.', 'revolt-framework');
}



/**
 * Renders the contents of the given template to a string and returns it.
 *
 * @param string $template_name The name of the template to render (without .php)
 * @param array  $attributes    The PHP variables for the template
 *
 * @return string               The contents of the template.
 */
function get_template_html($template_name, $attributes = null)
{
    if (!$attributes) {
        $attributes = array();
    }

    /**
     * Notes:
     * The output buffer collects everything that is printed between 
     * ob_start and ob_end_clean so that it can then be retrieved as a string using ob_get_contents.
     * 
     * Notes: the do actions are called by add action, gives chance to other devs to add further customizations
     */
    ob_start();

    do_action('revolt_customize_before_' . $template_name);

    require(REVOLT_FRAMEWORK_DIR . 'frontend/html-templates/' . $template_name . '.php');

    do_action('revolt_customize_after_' . $template_name);

    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

/**
 * Checks that the reCAPTCHA parameter sent with the registration
 * request is valid.
 *
 * @return bool True if the CAPTCHA is OK, otherwise false.
 */
function verify_recaptcha()
{
    // This field is set by the recaptcha widget if check is successful
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha_response = $_POST['g-recaptcha-response'];
    } else {
        return false;
    }

    // Verify the captcha response from Google
    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        array(
            'body' => array(
                'secret' => get_option('revolt-recaptcha-secret-key'),
                'response' => $captcha_response
            )
        )
    );

    $success = false;
    if ($response && is_array($response)) {
        $decoded_response = json_decode($response['body']);
        $success = $decoded_response->success;
    }

    return $success;
}
