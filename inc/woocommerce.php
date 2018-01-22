<?php

use Automattic\WooCommerce\HttpClient\HttpClientException;
use Automattic\WooCommerce\Client;

/**
 * Connect to the WooCommerce API.
 *
 * @return \Automattic\WooCommerce\Client|bool
 */
function jck_api_connect() {
	static $connection;

	if ( isset( $connection ) ) {
		return $connection;
	}

	$keys = jck_get_user_keys();

	if ( ! $keys ) {
		$connection = false;

		return $connection;
	}

	$connection = new Client(
		$keys['store_url'],
		$keys['consumer_key'],
		$keys['consumer_secret'],
		array(
			'wp_api'     => true,
			'version'    => 'wc/v2',
			'verify_ssl' => false, // Allow self-signed certificates (remove for prod)
		)
	);

	return $connection;
}

/**
 * Get collection.
 *
 * @param string $type customers|orders|products
 * @param array  $args
 *
 * @return mixed
 */
function jck_api_get_collection( $type, $args = array() ) {
	if ( empty( $type ) ) {
		return false;
	}

	static $result = array();

	$args['page']     = isset( $args['page'] ) ? $args['page'] : jck_get_current_page();
	$args['per_page'] = isset( $args['per_page'] ) ? $args['per_page'] : jck_get_current_per_page();

	$key = sprintf( '%s-%s', $type, md5( serialize( $args ) ) );

	if ( isset( $result[ $key ] ) ) {
		return $result[ $key ];
	}

	$connection = jck_api_connect();

	if ( ! $connection ) {
		$result[ $key ] = false;

		return $result[ $key ];
	}

	try {
		$result[ $key ] = $connection->get( $type, $args );
	} catch ( HttpClientException $e ) {
		$result[ $key ] = false;
		jck_add_notice( $e->getMessage() );
	}

	return $result[ $key ];
}

/**
 * Update collection item.
 *
 * @param string $type customers|orders|products
 * @param int    $id
 * @param array  $args
 *
 * @return array|bool
 */
function jck_api_update_collection_item( $type, $id, $args = array() ) {
	$connection = jck_api_connect();

	if ( ! $connection || empty( $id ) || empty( $type ) ) {
		return false;
	}

	$request = sprintf( '%s/%d', $type, $id );

	try {
		$result = $connection->put( $request, $args );
	} catch ( HttpClientException $e ) {
		$result = false;
		jck_add_notice( $e->getMessage() );
	}

	return $result;
}

/**
 * Get collection item.
 *
 * @param string $type customers|orders|products
 * @param int    $id
 * @param array  $args
 *
 * @return array|bool
 */
function jck_api_get_collection_item( $type, $id, $args = array() ) {
	$connection = jck_api_connect();

	if ( ! $connection || empty( $id ) || empty( $type ) ) {
		return false;
	}

	$request = sprintf( '%s/%d', $type, $id );

	try {
		$result = $connection->get( $request, $args );
	} catch ( HttpClientException $e ) {
		$result = false;
		jck_add_notice( $e->getMessage() );
	}

	return $result;
}

/**
 * Delete collection item.
 *
 * @param string $type customers|orders|products
 * @param int    $id
 * @param array  $args
 *
 * @return array|bool
 */
function jck_api_delete_collection_item( $type, $id, $args = array() ) {
	$connection = jck_api_connect();

	if ( ! $connection || empty( $id ) || empty( $type ) ) {
		return false;
	}

	$request       = sprintf( '%s/%d', $type, $id );
	$args['force'] = isset( $args['force'] ) ? $args['force'] : true;

	try {
		$result = $connection->delete( $request, $args );
	} catch ( HttpClientException $e ) {
		$result = false;
		jck_add_notice( $e->getMessage() );
	}

	return $result;
}