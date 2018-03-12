<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/setup.php' );

$delete_keys = iconic_delete_user_keys();

header( "Location: " . iconic_get_app_url() );
die();