<?php

/**
 * Add notice.
 *
 * @param string $notice
 * @param string $type
 */
function iconic_add_notice( $notice, $type = 'danger' ) {
	$notices = isset( $_SESSION['iconic_notices'] ) ? $_SESSION['iconic_notices'] : array();

	$notices[] = array(
		'notice' => $notice,
		'type'   => $type,
	);

	$_SESSION['iconic_notices'] = $notices;
}

/**
 * Delete all notices.
 */
function iconic_delete_notices() {
	unset( $_SESSION['iconic_notices'] );
}

/**
 * Display notices.
 */
function iconic_display_notices() {
	if ( empty( $_SESSION['iconic_notices'] ) ) {
		return;
	}

	foreach( $_SESSION['iconic_notices'] as $notice ) {
		?>
		<div class="alert alert-<?php echo $notice['type']; ?>" role="alert">
			<?php echo $notice['notice']; ?>
		</div>
		<?php
	}

	iconic_delete_notices();
}