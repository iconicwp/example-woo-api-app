<?php

/**
 * Add notice.
 *
 * @param string $notice
 * @param string $type
 */
function jck_add_notice( $notice, $type = 'danger' ) {
	$notices = isset( $_SESSION['jck_notices'] ) ? $_SESSION['jck_notices'] : array();

	$notices[] = array(
		'notice' => $notice,
		'type'   => $type,
	);

	$_SESSION['jck_notices'] = $notices;
}

/**
 * Delete all notices.
 */
function jck_delete_notices() {
	unset( $_SESSION['jck_notices'] );
}

/**
 * Display notices.
 */
function jck_display_notices() {
	if ( empty( $_SESSION['jck_notices'] ) ) {
		return;
	}

	foreach( $_SESSION['jck_notices'] as $notice ) {
		?>
		<div class="alert alert-<?php echo $notice['type']; ?>" role="alert">
			<?php echo $notice['notice']; ?>
		</div>
		<?php
	}

	jck_delete_notices();
}