<?php
if ($this->session->flashdata('add_success')) {
	echo '<p class="alert alert-success">';
	echo $this->session->flashdata('add_success');
	echo '</p>';
}
?>

<h1 style="text-align: center">
	<?php echo $title;?>
</h1>
<div class="container">
	<?php
		$url = base_url();
		$url .= "assets/images/";
		if (isset($images)) {
			foreach ($images as $image) {
				if ($image != NULL) {
					echo "<img src='$url$image' alt='img' class='img-thumbnail'
		 style='height: 400px; width: 400px; margin: auto; display: block;'>";
				}
			}
		}
	?>

	<table class="table table-hover col-md-8">
		<tr>
			<td><strong>Price</strong></td>
			<td><?php echo $price; ?></td>
		</tr>
		<tr>
			<td><strong>Description</strong></td>
			<td><?php echo $description; ?></td>
		</tr>
	</table>

	<form class="container" method="post" action="/auction/users/load_details/<?php echo $id; ?>">
		<?php if (isset($_SESSION['username'])) :?>
			<button type="submit" class="btn btn-primary" name="cart" id="">Add to Shopping Cart</button>
		<?php endif;?>
	</form>
</div>

<div id="">
	<form class="container" >
		<label>Add your reviews</label>
		<textarea class="form-control" name="review" id="content" required rows="3"></textarea>
		<button type="submit" class="btn btn-primary" id="reviews">Post</button>
	</form>
</div>

<div class="container">
	<div id="comments" class="col-md-12 col-md-offset-2 ">

	</div>
</div>


<script>
	$(document).ready(function () {
		load_reviews();

		function load_reviews(query) {
			$.ajax({
				url:"<?php echo base_url(); ?>users/load_reviews/<?php echo $id; ?>",
				method:"POST",
				data:{query:query},
				success:function (response) {
					$('#comments').html("");
					if (response == "No reviews") {
						$('#comments').html(response);
					} else {
						var obj = JSON.parse(response);
						if (obj.length > 0) {
							var reviews = [];
							$.each(obj, function (i, val) {
								reviews.push($("<p>").html("<strong>" + val.username + ":<strong> " + val.review));
								reviews.push($("<p>").text(val.time));
								// reviews.push($("<p>").text(val.review));
							});
							$("#comments").append.apply($("#comments"), reviews);
						} else {
							$('#comments').html(response);
						}
					}

				}
			});

		}

		$('#reviews').click(function (e) {
			e.preventDefault();
			var review = $('#content').val();
			if (review != "") {
				load_reviews(review);
			}
		});
	});
</script>
