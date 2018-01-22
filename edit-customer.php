<?php require_once( 'inc/partials/header.php' ); ?>

	<div class="page-header">
		<h1>Edit Customer</h1>
	</div>

	<?php jck_display_notices(); ?>

	<?php
	$customer_id = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$customer = jck_api_get_collection_item( 'customers', $customer_id );
	?>

	<?php if ( $customer ) { ?>
		<?php $fields = jck_get_edit_customer_fields(); ?>

		<form action="<?php echo jck_get_current_url( array( 'action' => 'update_customer' ) ); ?>" method="post">
			<?php foreach( $fields as $field_id => $field ) { ?>
				<div class="form-group">
					<label for="<?php echo $field_id; ?>"><?php echo $field['label']; ?></label>
					<?php printf(
						'<input type="%1$s" class="form-control" id="%2$s" placeholder="%3$s" name="%2$s" value="%4$s">',
						$field['type'],
						$field_id,
						$field['placeholder'],
						$customer->{$field['parameter']}
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