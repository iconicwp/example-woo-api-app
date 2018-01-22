<?php

session_start();

require_once( $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/functions.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/form.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/database.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/user.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/woocommerce.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/actions.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/notices.php' );

jck_process_actions();