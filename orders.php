<?php require_once( 'inc/partials/header.php' ); ?>

	<div class="page-header">
		<h1>Orders</h1>
	</div>

	<?php iconic_display_notices(); ?>

	<?php $orders = iconic_api_get_collection( 'orders' ); ?>

	<?php if ( ! empty( $orders ) ) { ?>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Status</th>
					<th scope="col">Customer ID</th>
					<th scope="col">Items</th>
					<th scope="col">Total</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $orders as $order ) { ?>
					<tr>
						<th scope="row"><?php echo $order->id; ?></th>
						<td><?php echo iconic_get_status_badge( $order->status ); ?></td>
						<td><?php echo $order->customer_id; ?></td>
						<td><?php echo count( $order->line_items ); ?></td>
						<td><?php echo $order->total; ?> <?php echo $order->currency; ?></td>
						<td>
							<?php if ( $order->status === 'completed' ) { ?>
								<a href="<?php echo iconic_get_current_url( array(
									'action' => 'order_status',
									'status' => 'processing',
									'order_id' => $order->id,
								) ); ?>" class="btn btn-default">Mark Processing</a>
							<?php } else { ?>
								<a href="<?php echo iconic_get_current_url( array(
									'action' => 'order_status',
									'status' => 'completed',
									'order_id' => $order->id,
								) ); ?>" class="btn btn-default">Mark Complete</a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php iconic_display_pagination_links( 'orders' ); ?>
	<?php } else { ?>
		<div class="alert alert-warning" role="alert">
			<p>Sorry, no orders were found.</p>
		</div>
	<?php } ?>

<?php require_once( 'inc/partials/footer.php' ); ?>