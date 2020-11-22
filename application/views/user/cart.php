<?php if (isset($_SESSION['username'])) : ?>
<div class="container" style="text-align: center">
	<?php
		if (isset($cart)) {
			$url = base_url();
			$url .= "assets/images/";
			foreach ($cart as $row) {
				echo "
					<h3>$row->title</h3>
					<img src='$url$row->image0' alt='img' class='img-thumbnail'
		 style='height: 200px; width: 200px; margin: auto; display: block;'>
		 			<p>Price: $$row->price</p>
		 			<form method='post' action='delete_item'>
		 				<input type='text' name='product_id' value='$row->id_cart' style='display: none'>
		 				<button type='submit' class='btn btn-primary' name='delete_item' >Delete</button>
					</form>
				";
			}
		}
	?>
</div>
<?php else:?>
<div class="container">
	<h3>Please login first.</h3>

</div>

<?php endif;?>
