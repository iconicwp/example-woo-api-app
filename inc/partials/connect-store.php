<?php $store_url = jck_get_store_url(); ?>

<?php if ( $store_url ) { ?>
	<div class="page-header">
		<h1>Dashboard</h1>
	</div>

	<?php jck_display_notices(); ?>

	<div class="alert alert-info" role="alert">
		<p>Your store "<?php echo $store_url; ?>" is connected. <a href="/disconnect.php">Disconnect?</a></p>
	</div>

	<?php return; ?>
<?php } ?>

<div class="page-header">
	<h1>Connect to WooCommerce</h1>
</div>

<?php jck_display_notices(); ?>

<div class="alert alert-warning" role="alert">
	<p>Please connect your WooCommerce store.</p>
</div>

<form action="http://sm-app.local/connect.php" method="post">
	<div class="form-group">
		<label for="store_url">Store URL</label>
		<input type="url" class="form-control" id="store_url" name="store_url" placeholder="E.g. https://example.com/">
	</div>
	<input type="hidden" name="token" value="<?php echo jck_generate_form_token( 'jck-connect' ); ?>">
	<button type="submit" class="btn btn-default" name="jck-connect">Connect</button>
</form>