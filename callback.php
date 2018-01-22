<?php
/**
 * The auth endpoint will send the API Keys in JSON format to the callback_url,
 * so it’s important to remember that some languages such as PHP will not display
 * it inside the $_POST global variable, in PHP you can access it using
 * $HTTP_RAW_POST_DATA (for old PHP versions) or file_get_contents('php://input');.
 */
$post_data = file_get_contents("php://input");

if ( empty( $post_data ) ) {
	http_response_code( 400 );
	die;
}

$post_data = json_decode( $post_data );

if( empty( $post_data->key_id ) ) {
	http_response_code( 400 );
	die;
}

require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/setup.php' );

/**
 * Store the keys as they are, but in the real world, focus on securing your overall app to prevent
 * the keys becoming compromised. For simplicity, this app is not secure and is purely used as
 * and example to get you started interacting with the API.
 */

$user_id = (int) filter_var( $post_data->user_id, FILTER_SANITIZE_NUMBER_INT );
$consumer_key = filter_var( $post_data->consumer_key, FILTER_SANITIZE_STRING );
$consumer_secret = filter_var( $post_data->consumer_secret, FILTER_SANITIZE_STRING );

$insert_user_keys = jck_db_insert_user_keys( $user_id, $consumer_key, $consumer_secret );

if ( ! $insert_user_keys ) {
	http_response_code( 500 );
	die;
}