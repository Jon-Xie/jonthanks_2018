<?php 
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		#map-container{
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
	</style>
</head>
<body>
	<div id="map-container"></div>
	<script>
      let locations = [
        {
          lat: 34.9530,
          lng: 120.4357
        },
        {
          lat: 32.1122, 
          lng: 117.1122
        },
        {
          lat: 34.1122, 
          lng: 120.1122
        }
      ];
      let map;
      function loadMyMap() {
        map = new google.maps.Map(document.getElementById('map-container'), {
          center: {lat: 40.776993, lng: -111.925221},
          zoom: 12,
        });

        for(let i =0; i<locations.length; i++){
          let location = locations[i];
          let marker = new google.maps.Marker({position: location, map: map});
        }
       
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGy5cMHbtKc22oEc1xZivbOFShVIa3IDs&callback=loadMyMap"
    async defer></script>
</body>
</html>