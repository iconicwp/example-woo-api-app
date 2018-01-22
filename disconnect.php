<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/setup.php' );

$delete_keys = jck_delete_user_keys();

header( "Location: " . jck_get_app_url() );
die();