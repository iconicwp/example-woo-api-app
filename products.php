<?php require_once( 'inc/partials/header.php' ); ?>

	<div class="page-header">
		<h1>Products</h1>
	</div>

	<?php iconic_display_notices(); ?>

	<?php $products = iconic_api_get_collection( 'products' ); ?>

	<?php if ( ! empty( $products ) ) { ?>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Name</th>
					<th scope="col">Type</th>
					<th scope="col">Price</th>
					<th scope="col">Status</th>
					<th scope="col">Visibility</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $products as $product ) { ?>
					<tr>
						<th scope="row"><?php echo $product->id; ?></th>
						<td><?php echo $product->name; ?></td>
						<td class="text-capitalize"><?php echo $product->type; ?></td>
						<td><?php echo ! empty( $product->price_html ) ? $product->price_html : "&mdash;"; ?></td>
						<td><?php echo iconic_get_status_badge(  $product->status ); ?></td>
						<td><?php echo iconic_get_status_badge( $product->catalog_visibility ); ?></td>
						<td>
							<a href="/edit-product.php?id=<?php echo $product->id; ?>" class="btn btn-default">Edit</a>
							<a href="<?php echo iconic_get_current_url( array(
								'action' => 'delete_collection_item',
								'type' => 'products',
								'id' => $product->id,
							) ); ?>" class="btn btn-default">Delete</a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php iconic_display_pagination_links( 'products' ); ?>
	<?php } else { ?>
		<div class="alert alert-warning" role="alert">
			<p>Sorry, no products were found.</p>
		</div>
	<?php } ?>

<?php require_once( 'inc/partials/footer.php' ); ?>