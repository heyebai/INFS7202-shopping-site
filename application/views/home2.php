<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewpoint" content="width=decice-width, initial-scale=1">
		<title>online auction</title>
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
			  rel="stylesheet">
	</head>
	<body >
		<header class="container">
			<div class="row">
				<div class="col-md-2">
					<h3 style="font-family: 'Pacifico', cursive;"><a href="#" >MyAuction</a></h3>
				</div>
<!--				 searching bar-->
				<div class="col-md-7">
					<form class="form-inline">
						<input type="text" name="anything" placeholder="Searching for anything" class="form-control" style="width: 70%">
						<input type="submit" name="searching" value="Search" class="btn btn-primary">
					</form>
				</div>
				<div class="col-md-1">
					<button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Sign in/Register"><span class="material-icons">how_to_reg</span></button>
				</div>
				<div class="col-md-1">
					<button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Favorite"><span class="material-icons">star</span></button>
				</div>
				<div class="col-md-1">
					<button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Shopping cart"><span class="material-icons">shopping_cart</span></button>
				</div>
			</div>
		</header>
		<div>
		 <?php $this->load->view('user/login'); ?>
		</div>
		<footer>
			<p>Copyright 2020</p>
		</footer>

		<script src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

		<script>
			$(function () { $("[data-toggle='tooltip']").tooltip(); });
		</script>
	</body>
</html>

