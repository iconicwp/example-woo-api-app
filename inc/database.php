<?php

/**
 * Connect to the DB.
 *
 * @return mysqli|string
 */
function jck_db_connect() {
	// Define connection as a static variable, to avoid connecting more than once
	static $connection;

	// Try and connect to the database, if a connection has not been established yet
	if ( ! isset( $connection ) ) {
		// Load configuration as an array. Use the actual location of your configuration file
		$config     = parse_ini_file( $_SERVER['DOCUMENT_ROOT'] . '/config.ini' );
		$connection = mysqli_connect( 'localhost', $config['username'], $config['password'], $config['dbname'] );
	}

	// If connection was not successful, handle the error
	if ( $connection === false ) {
		// Handle error - notify administrator, log to a file, show an error screen, etc.
		return mysqli_connect_error();
	}

	return $connection;
}

/**
 * Query the DB.
 *
 * @param string $query
 *
 * @return bool|mysqli_result
 */
function jck_db_query( $query ) {
	// Connect to the database
	$connection = jck_db_connect();

	// Query the database
	$result = mysqli_query( $connection, $query );

	return $result;
}

/**
 * Return DB error.
 *
 * @return string
 */
function jck_db_error() {
	$connection = jck_db_connect();

	return mysqli_error( $connection );
}

/**
 * Escape string before inserting to DB.
 *
 * @param string $value
 *
 * @return string
 */
function jck_db_escape( $value ) {
	$connection = jck_db_connect();

	return mysqli_real_escape_string( $connection, $value );
}

/**
 * Insert user keys.
 *
 * @param int    $user_id
 * @param string $consumer_key
 * @param string $consumer_secret
 *
 * @return bool
 */
function jck_db_insert_user_keys( $user_id, $consumer_key, $consumer_secret ) {
	if ( empty( $user_id ) ) {
		return false;
	}

	$result = jck_db_query( sprintf(
		'
		INSERT INTO `keys` ( `user_id`, `consumer_key`, `consumer_secret` ) 
		VALUES ( "%d", "%2$s", "%3$s" )
		ON DUPLICATE KEY UPDATE
		`consumer_key` = "%2$s", `consumer_secret` = "%3$s"
		',
		$user_id,
		jck_db_escape( $consumer_key ),
		jck_db_escape( $consumer_secret )
	) );

	return $result;
}

/**
 * Insert store URL.
 *
 * @param $url
 *
 * @return bool|mysqli_result
 */
function jck_db_insert_store_url( $url ) {
	$user_id = jck_get_user_id();

	$result = jck_db_query( sprintf(
		'
		INSERT INTO `keys` ( `user_id`, `store_url` ) 
		VALUES ( "%d", "%2$s" )
		ON DUPLICATE KEY UPDATE
		`store_url` = "%2$s"
		',
		$user_id,
		jck_db_escape( $url )
	) );

	return $result;
}

/**
 * Get user keys by ID.
 *
 * @param $user_id
 *
 * @return bool|array
 */
function jck_db_get_user_keys( $user_id ) {
	if ( ! $user_id ) {
		return false;
	}

	static $keys = array();

	if ( ! empty( $keys[ $user_id ] ) ) {
		return $keys[ $user_id ];
	}

	$result = jck_db_query( sprintf(
		'
		SELECT consumer_key, consumer_secret, store_url FROM `keys`
		WHERE `user_id` = "%d"
		LIMIT 1
		',
		$user_id
	) );

	$row = mysqli_fetch_assoc( $result );

	if ( ! $row || empty( $row['consumer_key'] ) ) {
		$keys[ $user_id ] = false;

		return $keys[ $user_id ];
	}

	$keys[ $user_id ] = $row;

	return $keys[ $user_id ];
}

/**
 * Get user keys by ID.
 *
 * @param $user_id
 *
 * @return bool
 */
function jck_db_delete_user_keys( $user_id ) {
	if ( ! $user_id ) {
		return false;
	}

	$result = jck_db_query( sprintf(
		'
		DELETE FROM `keys`
		WHERE `user_id` = "%d"
		',
		$user_id
	) );

	return $result;
}