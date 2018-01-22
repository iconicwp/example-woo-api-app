<?php

/**
 * Get user ID.
 *
 * @return int
 */
function jck_get_user_id() {
	return 1;
}

/**
 * Get current user keys.
 *
 * @return bool|array
 */
function jck_get_user_keys() {
	$user_id = jck_get_user_id();

	return jck_db_get_user_keys( $user_id );
}

/**
 * Delete current user keys.
 *
 * @return bool
 */
function jck_delete_user_keys() {
	$user_id = jck_get_user_id();

	return jck_db_delete_user_keys( $user_id );
}

/**
 * Get store URL.
 *
 * @return bool|string
 */
function jck_get_store_url() {
	$keys = jck_get_user_keys();

	if ( ! $keys ) {
		return false;
	}

	return $keys['store_url'];
}