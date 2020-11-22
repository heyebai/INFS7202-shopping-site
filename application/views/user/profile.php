<?php
if ($this->session->flashdata('update_fail')) {
	echo '<p class="alert alert-danger">';
	echo $this->session->flashdata('update_fail');
	echo '</p>';
}

if ($this->session->flashdata('updated')) {
	echo '<p class="alert alert-success">';
	echo $this->session->flashdata('updated');
	echo '</p>';
}

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

if ($this->session->flashdata('watermark_success')) {
	echo '<p class="alert alert-success">';
	echo $this->session->flashdata('watermark_success');
	echo '</p>';
}

?>

<h1 style="text-align: center">Personal info</h1>
<div class="container">
	<div class="col-md-8 col-md-offset-2" >
		<h3>Profile</h3>
		<table class="table table-hover">
			<tr>
				<th>PHOTO</th>
				<td>
					<?php $image = $this->User_model->check_image();?>
					<?php if ($image) :?>
						<img id="img_display" src="<?php echo base_url(); ?>assets/images/<?php echo $image->image;?>" alt="avatar" class="img-circle" style="height: 150px; width: 150px">
					<?php else:?>
						<img id="img_display" src="<?php echo base_url(); ?>assets/images/2.jpeg" alt="avatar" class="img-circle" style="height: 150px; width: 150px">
					<?php endif;?>

				<form action="upload" method="post" enctype="multipart/form-data">
					<input type="file" name="image" class="btn btn-default">
					<input type="submit" name="upload" value="Upload Image" class="btn btn-primary" id="image_submit">
				</form>

				<div class="dropZone" id="dropZone">
					Just drag and drop image here to upload.
				</div>

				<form action="watermark" method="post">
					<?php if ($image) :?>
						<input type="submit" name="" value="Add watermark" class="btn btn-primary" >
					<?php else:?>
						<input type="submit" value="Add watermark" class="btn btn-primary" disabled="disabled">
					<?php endif;?>
				</form>
				</td>
			</tr>
			<tr>
				<th>NAME</th>
				<td><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?></td>
			</tr>
			<tr>
				<th>USERNAME</th>
				<td><?php echo $this->session->userdata('username'); ?></td>
			</tr>
			<tr>
				<th>LOCATION</th>
				<td><input type="button" id="location" class="btn btn-primary" value="Get Location"></td>
			</tr>
		</table>

		<div id="locationholder"></div>
		<div id="mapholder"></div>

		<h3>Contact info</h3>
		<div>
			<form class="form-inline" method="post" action="update">
				<div class="form-group">
					<label style="font-size: large; color: darkgray; margin-right: 300px">EMAIL</label>
					<input type="email" name="email_u" id="email" class="form-control"  value="<?php echo $this->session->userdata('email');?>"
					 placeholder="<?php echo $this->session->userdata('email');?>">
				</div>
				<button type="submit" name="update" class="btn btn-primary">Update</button>
			</form>
			<?php if (!empty($_POST['update'])){
				echo validation_errors('<p class="alert alert-danger">','</p>');
			}
			?>
		</div>

	</div>
</div>
<!--<script src="https://maps.google.com/maps/api/js?sensor=false"></script>-->

<script>
	document.getElementById("location").onclick=function () {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				document.getElementById("locationholder").innerHTML = "Latitude: " + position.coords.latitude +
					"<br>Longitude: " + position.coords.longitude;
				document.getElementById("locationholder").className="alert alert-success";
				
				// lat=position.coords.latitude;
				// lon=position.coords.longitude;
				// latlon=new google.maps.LatLng(lat, lon)
				// mapholder=document.getElementById('mapholder')
				// mapholder.style.height='250px';
				// mapholder.style.width='500px';
				//
				// var myOptions={
				// 	center:latlon,zoom:14,
				// 	mapTypeId:google.maps.MapTypeId.ROADMAP,
				// 	mapTypeControl:false,
				// 	navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
				// };
				// var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
				// var marker=new google.maps.Marker({position:latlon,map:map,title:"You are here!"});

			});
		} else {
			alert("Geolocation is not support")
		}
	};
</script>

<script>
	mapholder=document.getElementById('mapholder');
	mapholder.style.height='300px';
	mapholder.style.width='500px';

	mapboxgl.accessToken = 'pk.eyJ1IjoiaGV5ZWJhaSIsImEiOiJjazlqaW8waGQwMjJlM2tscHpuODh5a3d6In0.ce_Au_XL1khYR31AkVz_tA';
	var map = new mapboxgl.Map({
		container: 'mapholder', // container id
		style: 'mapbox://styles/mapbox/streets-v11',
		center: [152.99, -27.49], // starting position
		zoom: 14 // starting zoom
	});

	// Add geolocate control to the map.
	map.addControl(
		new mapboxgl.GeolocateControl({
			positionOptions: {
				enableHighAccuracy: true
			},
			trackUserLocation: true
		})
	);
</script>

<!--CSS for drag and drop-->
<style>
	.dropZone {
		height: 150px;
		width: 500px;
		border: 1px dashed #CCCCCC;
		text-align: center;
		font-size: small;
		color: #CCCCCC;
		line-height: 150px;
	}

	.dropZone.dragOver {
		color: black;
		border-color: black;
	}
</style>

<!--js for drag and drop-->
<script>
	(function () {
		var dropZone = document.getElementById('dropZone');

		var upload_image = function (files) {
			var formData = new FormData(),
				xhr = new XMLHttpRequest(),
				email = document.getElementById('email').getAttribute("placeholder");

			formData.append('email', email);
			formData.append('image[]', files[0]);

			xhr.onload = function(){
				var data = JSON.parse(this.responseText);
				//console.log(data[0].name);
				document.getElementById('img_display').setAttribute('src', 'https://infs3202-0b096f23.uqcloud.net/auction/assets/images/' + data[0].name);
			}

			xhr.open('post','https://infs3202-0b096f23.uqcloud.net/auction/users/drop_upload')
			xhr.send(formData);
		}

		dropZone.ondrop = function(e) {
			e.preventDefault();
			this.className = 'dropZone';
			upload_image(e.dataTransfer.files)
		};

		dropZone.ondragover = function () {
			this.className = 'dropZone dragOver';
			return false;
		};

		dropZone.ondragleave = function () {
			this.className = 'dropZone';
			return false;
		};
	}())
</script>
