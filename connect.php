<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/setup.php' );

if ( ! iconic_verify_form_token( 'jck-connect' ) ) {
	iconic_add_notice( 'Could not verify form token.' );
	header( "Location: " . iconic_get_app_url() );
	die();
}

$url = filter_input( INPUT_POST, 'store_url', FILTER_SANITIZE_URL );

if ( empty( $url ) ) {
	iconic_add_notice( 'The URL was invalid.' );
	header( "Location: " . iconic_get_app_url() );
	die();
}

$url = iconic_add_trailing_slash( $url );
$auth_url = $url . iconic_get_authorize_path();
$auth_url = iconic_add_auth_params( $auth_url );

if ( ! iconic_url_exists( $auth_url ) ) {
	iconic_add_notice( 'The URL was invalid.' );
	header( "Location: " . iconic_get_app_url() );
	die();
}

$insert_store_url = iconic_db_insert_store_url( $url );

if ( ! $insert_store_url ) {
	iconic_add_notice( 'The connection failed, please try again.', 'warning' );
	header( "Location: " . iconic_get_app_url() );
	die();
}

header( "Location: " . $auth_url );
die();