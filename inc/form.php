<?php

/**
 * Generate unique form token.
 *
 * @param string $form
 *
 * @return string
 */
function iconic_generate_form_token( $form ) {
	// generate a token from an unique value
	$token = md5( uniqid( microtime(), true ) );

	// Write the generated token to the session variable to check it against the hidden field when the form is sent
	$_SESSION[ $form . '_token' ] = $token;

	return $token;
}

/**
 * Verify form token.
 *
 * @param string $form
 *
 * @return bool
 */
function iconic_verify_form_token( $form ) {
	// check if a session is started and a token is transmitted, if not return an error
	if ( ! isset( $_SESSION[ $form . '_token' ] ) ) {
		return false;
	}

	// check if the form is sent with token in it
	if ( ! isset( $_POST['token'] ) ) {
		return false;
	}

	// compare the tokens against each other if they are still the same
	if ( $_SESSION[ $form . '_token' ] !== $_POST['token'] ) {
		return false;
	}

	return true;
}