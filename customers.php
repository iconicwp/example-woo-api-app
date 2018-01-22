<?php require_once( 'inc/partials/header.php' ); ?>

	<div class="page-header">
		<h1>Customers</h1>
	</div>

	<?php jck_display_notices(); ?>

	<?php $customers = jck_api_get_collection( 'customers', array(
		'orderby' => 'id',
	) ); ?>

	<?php if ( ! empty( $customers ) ) { ?>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">First Name</th>
					<th scope="col">Last Name</th>
					<th scope="col">Email</th>
					<th scope="col">Total Orders</th>
					<th scope="col">Total Spent</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $customers as $customer ) { ?>
					<tr>
						<th scope="row"><?php echo $customer->id; ?></th>
						<td><?php echo $customer->first_name; ?></td>
						<td><?php echo $customer->last_name; ?></td>
						<td><?php echo $customer->email; ?></td>
						<td><?php echo $customer->orders_count; ?></td>
						<td><?php echo $customer->total_spent; ?></td>
						<td>
							<a href="/edit-customer.php?id=<?php echo $customer->id; ?>" class="btn btn-default">Edit</a>
							<a href="<?php echo jck_get_current_url( array(
								'action' => 'delete_collection_item',
								'type' => 'customers',
								'id' => $customer->id,
							) ); ?>" class="btn btn-default">Delete</a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php jck_display_pagination_links( 'customers' ); ?>
	<?php } else { ?>
		<div class="alert alert-warning" role="alert">
			<p>Sorry, no customers were found.</p>
		</div>
	<?php } ?>

<?php require_once( 'inc/partials/footer.php' ); ?>