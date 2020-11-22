<html>
<head>
	<meta charset="utf-8" />
	<title>Locate the user</title>
<!--	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />-->
	<script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
	<link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet" />
	<style>
		body { margin: 0; padding: 0; }
		#map { position: absolute; top: 0; bottom: 0; width: 100%; }
	</style>
</head>
<body>
<div id="map" style="height: 300px; width: 400px"></div>
<script>
	mapboxgl.accessToken = 'pk.eyJ1IjoiaGV5ZWJhaSIsImEiOiJjazlqaW8waGQwMjJlM2tscHpuODh5a3d6In0.ce_Au_XL1khYR31AkVz_tA';
	var map = new mapboxgl.Map({
		container: 'map', // container id
		style: 'mapbox://styles/mapbox/streets-v11',
		center: [152.99, -27.49], // starting position
		zoom: 10 // starting zoom
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

</body>
</html>
