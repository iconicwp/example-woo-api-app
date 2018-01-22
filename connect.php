<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/setup.php' );

if ( ! jck_verify_form_token( 'jck-connect' ) ) {
	jck_add_notice( 'Could not verify form token.' );
	header( "Location: " . jck_get_app_url() );
	die();
}

$url = filter_input( INPUT_POST, 'store_url', FILTER_SANITIZE_URL );

if ( empty( $url ) ) {
	jck_add_notice( 'The URL was invalid.' );
	header( "Location: " . jck_get_app_url() );
	die();
}

$url = jck_add_trailing_slash( $url );
$auth_url = $url . jck_get_authorize_path();
$auth_url = jck_add_auth_params( $auth_url );

if ( ! jck_url_exists( $auth_url ) ) {
	jck_add_notice( 'The URL was invalid.' );
	header( "Location: " . jck_get_app_url() );
	die();
}

$insert_store_url = jck_db_insert_store_url( $url );

if ( ! $insert_store_url ) {
	jck_add_notice( 'The connection failed, please try again.', 'warning' );
	header( "Location: " . jck_get_app_url() );
	die();
}

header( "Location: " . $auth_url );
die();