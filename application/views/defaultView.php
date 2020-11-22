<div class="container">
	<form class="form-inline col-md-8 col-md-offset-2 searching" style="padding: 5em">
		<input type="text" name="anything" id="search_bar" placeholder="Searching for anything" class="form-control input-lg" style="width: 80%">
		<input type="submit" name="searching" value="Search" class="btn btn-primary btn-lg" style="width: 15%">
	</form>
</div>

<div class="col-md-12 col-md-offset-6 centered" id="desplay">
<!--	<h3></h3>-->
<!--	<div></div>-->
<!--	<p style="display: none"></p>-->
</div>

<div class="container">
	<fieldset class="col-md-8 col-md-offset-2">
		<legend>Recently viewed</legend>
	</fieldset>
</div>

<div class="container">
	<fieldset class="col-md-8 col-md-offset-2">
		<legend>Popular items</legend>
	</fieldset>
</div>



<script>
	// var search_bar = document.getElementById("search_bar");
	//	//
	//	// search_bar.onkeyup = function autocompletion() {
	//	//
	//	// }
	$(document).ready(function(){

	$("#search_bar").keyup(function () {
		var search = $(this).val();
		if (search != "") {
			load_data(search);
		} else {
			$("#desplay").html("");
		}
	});

	function load_data(query) {
		$.ajax({
			url:"<?php echo base_url(); ?>users/autocompletion",
			method:"POST",
			data:{query:query},
			success:function (response) {
				$("#desplay").html("");
				if (response == "No Data Found") {
					$("#desplay").html(response);
				} else {
					var obj = JSON.parse(response);
					if (obj.length > 0) {
						var items = [];
						$.each(obj, function (i, val) {
							items.push($("<h3>").html("<a href='<?php echo base_url()?>users/load_details/"+val.id+"'>" + val.title + "</a>"));
							items.push($("<div>").text(val.description));
						});
						$("#desplay").append.apply($("#desplay"), items);
					} else {
						$("#desplay").html(response);
					}
				}
			}
		});
	}

	});
	
</script>
