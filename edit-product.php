<?php require_once( 'inc/partials/header.php' ); ?>

	<div class="page-header">
		<h1>Edit Product</h1>
	</div>

	<?php iconic_display_notices(); ?>

	<?php
	$product_id = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$product = iconic_api_get_collection_item( 'products', $product_id );
	?>

	<?php if ( $product ) { ?>
		<?php $fields = iconic_get_edit_product_fields( $product->type ); ?>

		<form action="<?php echo iconic_get_current_url( array( 'action' => 'update_product' ) ); ?>" method="post">
			<?php foreach( $fields as $field_id => $field ) { ?>
				<div class="form-group">
					<label for="<?php echo $field_id; ?>"><?php echo $field['label']; ?></label>
					<?php printf(
						'<input type="%1$s" class="form-control" id="%2$s" placeholder="%3$s" name="%2$s" value="%4$s">',
						$field['type'],
						$field_id,
						$field['placeholder'],
						$product->{$field['parameter']}
					); ?>
				</div>
			<?php } ?>

			<button type="submit" class="btn btn-primary">Update</button>
		</form>
	<?php } else { ?>
		<div class="alert alert-warning" role="alert">
			<p>Sorry, no customer was found for that ID.</p>
		</div>
	<?php } ?>

<?php require_once( 'inc/partials/footer.php' ); ?>