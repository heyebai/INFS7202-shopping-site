<?php
if ($this->session->flashdata('upload_failed')) {
	echo '<p class="alert alert-danger">';
	echo $this->session->flashdata('upload_failed');
	echo '</p>';
}

if ($this->session->flashdata('upload_success')) {
	echo '<p class="alert alert-success">';
	echo $this->session->flashdata('upload_success');
	echo '</p>';
}
?>

<form class="container product" method="post" action="createProduct" enctype="multipart/form-data">
	<div class="form-group">
		<label>Title</label>
<!--		<p>Use words people would search for when looking for your item.</p>-->
		<input type="text" class="form-control" name="title" required placeholder="Use words people would search for when looking for your item.">
	</div>
	<label>Price</label>
	<div class="input-group">
		<div class="input-group-addon">$</div>
		<input type="number" class="form-control" name="price" required placeholder="Amount (Only accept number)">
		<div class="input-group-addon">.00</div>
	</div>
	<div class="form-group">
		<label>Add photos</label>
		<input type="file" name="product_img[]" multiple>
	</div>
	<div class="input-group">
		<label>Description</label>
		<textarea class="form-control" name="description" required rows="8" cols="50"></textarea>
	</div>
	<button type="submit" class="btn btn-primary list_it btn-lg">List it</button>
</form>


<style>
	.product {
		margin-top: 2em;
	}

	.list_it {
		margin-top: 2em;
	}
</style>
