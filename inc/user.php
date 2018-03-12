<?php

/**
 * Get user ID.
 *
 * @return int
 */
function iconic_get_user_id() {
	return 1;
}

/**
 * Get current user keys.
 *
 * @return bool|array
 */
function iconic_get_user_keys() {
	$user_id = iconic_get_user_id();

	return iconic_db_get_user_keys( $user_id );
}

/**
 * Delete current user keys.
 *
 * @return bool
 */
function iconic_delete_user_keys() {
	$user_id = iconic_get_user_id();

	return iconic_db_delete_user_keys( $user_id );
}

/**
 * Get store URL.
 *
 * @return bool|string
 */
function iconic_get_store_url() {
	$keys = iconic_get_user_keys();

	if ( ! $keys ) {
		return false;
	}

	return $keys['store_url'];
}