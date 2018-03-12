<?php

/**
 * Get app URL.
 *
 * @return string
 */
function jck_get_app_url() {
	return 'https://iconic-app.local';
}

/**
 * Add trailing slash.
 *
 * @param string $string
 *
 * @return string
 */
function jck_add_trailing_slash( $string ) {
	$string = rtrim( $string, '/\\' );

	return $string . '/';
}

/**
 * Get current page URL.
 *
 * @param array $args
 *
 * @return string
 */
function jck_get_current_url( $args = array() ) {
	$app_url = jck_get_app_url() . $_SERVER['PHP_SELF'];

	parse_str( $_SERVER['QUERY_STRING'], $current_args );

	if ( ! empty( $args ) ) {
		foreach ( $args as $key => $value ) {
			if ( is_null( $value ) ) {
				unset( $current_args[ $key ] );
				continue;
			}

			$current_args[ $key ] = $value;
		}
	}

	$query_string = http_build_query( $current_args );

	$url = implode( '?', array_filter( array( $app_url, $query_string ) ) );

	return $url;
}

/**
 * get nav items.
 *
 * @return array
 */
function jck_get_nav_items() {
	$nav_items = array(
		'/' => 'Dashboard',
	);

	$store_url = jck_get_store_url();

	if ( $store_url ) {
		$nav_items['/orders.php']    = 'Orders';
		$nav_items['/customers.php'] = 'Customers';
		$nav_items['/products.php']  = 'Products';
	}

	return $nav_items;
}

/**
 * Get WooCommerce authorize path.
 *
 * @return string
 */
function jck_get_authorize_path() {
	return 'wc-auth/v1/authorize';
}

/**
 * URL exists?
 *
 * @param $url
 *
 * @return bool
 */
function jck_url_exists( $url ) {
	//check, if a valid url is provided
	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return false;
	}

	//initialize curl
	$curl_init = curl_init( $url );
	curl_setopt( $curl_init, CURLOPT_CONNECTTIMEOUT, 10 );
	curl_setopt( $curl_init, CURLOPT_HEADER, true );
	curl_setopt( $curl_init, CURLOPT_NOBODY, true );
	curl_setopt( $curl_init, CURLOPT_RETURNTRANSFER, true );

	// allow self-signed certs (remove for prod)
	curl_setopt( $curl_init, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl_init, CURLOPT_SSL_VERIFYHOST, false );

	//get answer
	$response = curl_exec( $curl_init );

	curl_close( $curl_init );

	if ( $response ) {
		return true;
	}

	return false;
}

/**
 * Add auth params to URL.
 *
 * @param string $url
 *
 * @return string
 */
function jck_add_auth_params( $url ) {
	$params = array(
		'app_name'     => 'WooCommerce App',
		'scope'        => 'read_write', // 'read', 'write', 'read_write'
		'user_id'      => jck_get_user_id(), // Local user ID
		'return_url'   => 'https://iconic-app.local/',
		'callback_url' => 'https://iconic-app.local/callback.php', // Must be https
	);

	/**
	 * For testing purposes, you need to add a filter to the woo store
	 * to allow posting to your localhost (for the callback_url):
	 *
	 * function jck_http_request( $r, $url ) {
	 *     $r['reject_unsafe_urls'] = false; // Allow unsafe callback_url (localhost)
	 *     $r['sslverify'] = false; // Allow self-signed cert
	 *
	 *     return $r;
	 * }
	 *
	 * add_filter( 'http_request_args', 'jck_http_request', 10, 2 );
	 *
	 * On top of this, if you're using local by flywheel, you may need to add a line to your hosts file:
	 *
	 * 1. Right-click on the WooCommerce site and go to “Open Site SSH”
	 * 2. Enter `/sbin/ip route|awk '/default/ { print $3 }' and copy the IP
	 * 3. Type `nano /etc/hosts` and add a new line at the bottom for the site you’re trying to access (the app).
	 *    It should look something like `172.17.0.1 test-1.dev`. Use the IP from above.
	 * 4. Test the connection. Note: This may be cleared if the server is restarted.
	 */

	// Add PHP_QUERY_RFC3986 so spaces are encoded as %20 and not +
	$query = http_build_query( $params, null, '&', PHP_QUERY_RFC3986 );

	return $url . '?' . $query;
}

/**
 * Get next or prev link.
 *
 * @param string $type customers|orders|products
 * @param string $next_prev
 *
 * @return string
 */
function get_next_prev_link( $type, $next_prev = 'next' ) {
	$current_page     = jck_get_current_page();
	$current_per_page = jck_get_current_per_page();

	if ( $next_prev === 'prev' && $current_page === 1 ) {
		return '';
	}

	$page = $next_prev === 'next' ? $current_page + 1 : $current_page - 1;

	if ( $page <= 0 ) {
		return '';
	}

	$orders = jck_api_get_collection( $type, array(
		'page'     => $page,
		'per_page' => $current_per_page,
	) );

	if ( empty( $orders ) ) {
		return '';
	}

	$label = $next_prev === 'next' ? sprintf( 'Page %d &rarr;', $page ) : sprintf( '&larr; Page %d', $page );
	$pull  = $next_prev === 'next' ? 'pull-right' : 'pull-left';

	$url = jck_get_current_url( array(
		'page' => $page === 1 ? null : $page,
	) );

	$link = sprintf(
		'<a href="%s" class="btn btn-default %s">%s</a>',
		$url,
		$pull,
		$label
	);

	return $link;
}

/**
 * Display pagination links.
 *
 * @param string $type
 */
function jck_display_pagination_links( $type ) {
	if ( empty ( $type ) ) {
		return;
	}

	$prev = get_next_prev_link( $type, 'prev' );
	$next = get_next_prev_link( $type, 'next' );
	?>
	<div>
		<?php echo $prev; ?>
		<?php echo $next; ?>
	</div>
	<?php
}

/**
 * Get current page.
 *
 * @return int
 */
function jck_get_current_page() {
	return isset( $_GET['page'] ) ? (int) $_GET['page'] : 1;
}

/**
 * Get current per_page.
 *
 * @return int
 */
function jck_get_current_per_page() {
	return isset( $_GET['per_page'] ) ? (int) $_GET['per_page'] : 15;
}

/**
 * Get status badge.
 *
 * @param string $status
 *
 * @return bool|string
 */
function jck_get_status_badge( $status = '' ) {
	if ( empty ( $status ) ) {
		return false;
	}

	switch ( $status ) {
		case "completed":
		case "publish":
		case "visible":
			$class = 'btn-success';
			break;
		case "cancelled":
			$class = 'btn-danger';
			break;
		default:
			$class = 'btn-warning';
	}

	return sprintf( '<span class="btn %s text-capitalize">%s</span>', $class, $status );
}

/**
 * Get edit customer fields.
 *
 * @return array
 */
function jck_get_edit_customer_fields() {
	return array(
		'email-address' => array(
			'label'       => 'Email Address',
			'parameter'   => 'email',
			'placeholder' => 'E.g. your@email.com',
			'type'        => 'email',
		),
		'first-name'    => array(
			'label'       => 'First Name',
			'parameter'   => 'first_name',
			'placeholder' => '',
			'type'        => 'text',
		),
		'last-name'     => array(
			'label'       => 'Last Name',
			'parameter'   => 'last_name',
			'placeholder' => '',
			'type'        => 'text',
		),
	);
}

/**
 * Get edit product fields.
 *
 * @param string $product_type
 *
 * @return array
 */
function jck_get_edit_product_fields( $product_type = 'simple' ) {
	$fields = array(
		'name' => array(
			'label'       => 'Name',
			'parameter'   => 'name',
			'placeholder' => '',
			'type'        => 'text',
		),
	);

	if ( $product_type !== 'variable' && $product_type !== 'composite' ) {
		$fields['regular-price'] = array(
			'label'       => 'Regular Price',
			'parameter'   => 'regular_price',
			'placeholder' => '',
			'type'        => 'number',
		);
		$fields['sale-price']    = array(
			'label'       => 'Sale Price',
			'parameter'   => 'sale_price',
			'placeholder' => '',
			'type'        => 'number',
		);
	}

	return $fields;
}