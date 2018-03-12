<?php

/**
 * Process actions.
 */
function iconic_process_actions() {
	$action = filter_input( INPUT_GET, 'action' );

	if ( ! $action ) {
		return;
	}

	$action_name = sprintf( 'iconic_action_%s', $action );

	if ( ! function_exists( $action_name ) ) {
		return;
	}

	$response = call_user_func( $action_name );

	header( "Location: " . $response );
	die();
}

/**
 * Update order status.
 */
function iconic_action_order_status() {
	$redirect = iconic_get_current_url( array(
		'action'   => null,
		'order_id' => null,
		'status'   => null,
	) );
	$order_id = (int) filter_input( INPUT_GET, 'order_id', FILTER_SANITIZE_NUMBER_INT );
	$status   = filter_input( INPUT_GET, 'status' );

	if ( ! $order_id || ! $status ) {
		iconic_add_notice( 'Order status could not be updated.' );

		return $redirect;
	}

	$update_status = iconic_api_update_collection_item( 'orders', $order_id, array(
		'status' => $status,
	) );

	if ( ! $update_status ) {
		return $redirect;
	}

	iconic_add_notice( sprintf( 'Order #%d status updated.', $order_id ), 'success' );

	return $redirect;
}

/**
 * Update customer.
 */
function iconic_action_update_customer() {
	$redirect    = iconic_get_current_url( array(
		'action' => null,
	) );
	$customer_id = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$email       = filter_input( INPUT_POST, 'email-address' );
	$first_name  = filter_input( INPUT_POST, 'first-name' );
	$last_name   = filter_input( INPUT_POST, 'last-name' );

	if ( ! $customer_id ) {
		iconic_add_notice( 'No customer ID was given.' );

		return $redirect;
	}

	$update_customer = iconic_api_update_collection_item( 'customers', $customer_id, array(
		'email'      => $email,
		'first_name' => $first_name,
		'last_name'  => $last_name,
	) );

	if ( ! $update_customer ) {
		iconic_add_notice( 'Sorry, the customer could not be updated at this time.' );

		return $redirect;
	}

	iconic_add_notice( 'Customer updated.', 'success' );

	return $redirect;
}

/**
 * Update product.
 */
function iconic_action_update_product() {
	$redirect      = iconic_get_current_url( array(
		'action' => null,
	) );
	$product_id    = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$name          = filter_input( INPUT_POST, 'name' );
	$regular_price = filter_input( INPUT_POST, 'regular-price', FILTER_SANITIZE_NUMBER_INT );
	$sale_price    = filter_input( INPUT_POST, 'sale-price', FILTER_SANITIZE_NUMBER_INT );

	if ( ! $product_id ) {
		iconic_add_notice( 'No product ID was given.' );

		return $redirect;
	}

	$update_product = iconic_api_update_collection_item( 'products', $product_id, array(
		'name'          => $name,
		'regular_price' => $regular_price,
		'sale_price'    => $sale_price,
	) );

	if ( ! $update_product ) {
		iconic_add_notice( 'Sorry, the product could not be updated.' );

		return $redirect;
	}

	iconic_add_notice( 'Product updated.', 'success' );

	return $redirect;
}

/**
 * Delete collection item.
 */
function iconic_action_delete_collection_item() {
	$redirect = iconic_get_current_url( array(
		'action' => null,
		'type'   => null,
		'id'     => null,
	) );
	$id       = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$type     = filter_input( INPUT_GET, 'type' );

	if ( ! $id || ! $type ) {
		iconic_add_notice( 'Item could not be deleted.' );

		return $redirect;
	}

	$delete_item = iconic_api_delete_collection_item( $type, $id );

	if ( ! $delete_item ) {
		return $redirect;
	}

	iconic_add_notice( sprintf( 'Item #%d deleted.', $id ), 'success' );

	return $redirect;
}