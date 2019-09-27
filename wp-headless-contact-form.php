<?php
/**
 * Plugin Name: WP Headless Contact form
 * Plugin URI: https://mejlak.com
 * Description: A simple plugin to send emails through the WordPress API
 * Version: 1.0
 * Author: Christian Mejlak
 * Author URI: https://mejlak.com
*/

defined('ABSPATH') or wp_die('Nope, not accessing this');

$website_domain = 'the_site_you_will_be_posting_from';
$send_to_email = 'YOUR_EMAIL_GOES_HERE';
$subject = 'EMAIL_SUBJECT_GOES_HERE';

//  ALLOW CROSS ORIGIN
add_action( 'init', 'allow_origin' );
function allow_origin() {
    header("Access-Control-Allow-Origin: {$website_domain}");
}

// REGISTER REST API NAMESPACE AND ENDPOINT
add_action('rest_api_init', 'api_endpoints');

function api_endpoints() {
    // you'll need to send post requests to:
    //https://yourdomain.com/wp-json/send-contact-form/v1/contact
    register_rest_route('send-contact-form/v1', 'contact', [
    'methods' => 'POST',
    'callback' => 'send_contact_form'
    ]);
    }

function send_contact_form(WP_REST_Request $request) {


    $name = sanitize_text_field( trim( $request['name']) );
    $email = sanitize_email( trim( $request['email'] ) );
    $body = wp_kses_post( trim( $request['message'] ) );
    $errors = [];

    if( empty( $name ) ) {
        $errors['name'] = 'Name is required';
    }

    if( empty( $email ) || ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        $errors['email'] = 'A valid email is required';
    }

    if( empty( $body ) ) {
        $errors['message'] = 'Message is required';
    }

    if( count($errors) ) {
        wp_send_json_error($errors);
    }

    $message = "Name: {$name} \n";
    $message .= "Email: {$email} \n";
    $message .= "Message: {$body}";

    wp_mail($send_to_email, $subject, $message, $headers);

    wp_send_json_success($request);
}
