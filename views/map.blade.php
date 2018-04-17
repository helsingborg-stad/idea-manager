@if(isset($locations) && is_array($locations) && !empty($locations))
    <script>
      var jsonPlots = {!!json_encode($locations)!!}
    </script>

	<div class="gutter gutter-top">
      <style scoped>
        .area-map {
          width: 100%;
        }
        #areaMap {
          height: 350px;
          margin-bottom: 30px;
        }
        #areaMap p {
          margin-top: 0px;
        }
      </style>

    <div class="area-map">
    	<div id="areaMap"></div>

    <script>
	function areaInitMap() {
		var markers = [],
	    	mapOptions = {
	        zoom: 14,
	        center: {!!json_encode($center)!!},
	        panControl: false,
	        zoomControl: true,
	        mapTypeControl: false,
	        scaleControl: false,
	        streetViewControl: false,
	        overviewMapControl: false,
	        mapTypeId: google.maps.MapTypeId.ROADMAP
	    	},
	    	map = new google.maps.Map(document.getElementById('areaMap'), mapOptions),
	    	iw = new google.maps.InfoWindow({maxWidth: 330});

	    function iwClose() { iw.close(); }
	    google.maps.event.addListener(map, 'click', iwClose);

		var oms = new OverlappingMarkerSpiderfier(map, {
	        markersWontMove: true,
	        markersWontHide: true,
      	});

		for (var i = 0, len = jsonPlots.length; i < len; i ++) {
			(function() {
				var markerData = jsonPlots[i];
				var html = '<h3>' + markerData.location + '</h3><p>' + markerData.excerpt + '</p><br><a target="_top" class="btn btn-md btn-primary" href="' + markerData.permalink + '"><?php _e("Read more", 'idea-manager'); ?></a>';
				var latLng = new google.maps.LatLng(markerData.geo.lat,markerData.geo.lng);
				var marker = new google.maps.Marker({
					position: latLng,
					title: markerData.location
				});
				google.maps.event.addListener(marker, 'click', iwClose);
				oms.addMarker(marker, function(e) {
					iw.setContent(html);
					iw.open(map, marker);
				});
				markers.push(marker);
			})();
		}

		// for debugging/exploratory use in console
		window.map = map;
		window.oms = oms;

	    // Make a cluster with the markers from the array
	    var imgPath = '{!! IDEAMANAGER_URL !!}/source/assets/images/';
	    var markerCluster = new MarkerClusterer(map, markers, { imagePath: imgPath, zoomOnClick: true, maxZoom: 17, gridSize: 20 });

	    google.maps.event.addListener(map, 'zoom_changed',
			function() {
				if (map.getZoom() > 18) {
				map.setZoom(18);
			};
		});
	}
	</script>

     	<script src="{!! IDEAMANAGER_URL !!}/source/js/vendor/MarkerClusterer.min.js"></script>
     	<script src="{!! IDEAMANAGER_URL !!}/source/js/vendor/OverlappingMarkerSpiderfier.min.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={!! G_GEOCODE_KEY !!}&callback=areaInitMap"></script>
    </div>
</div>
@endif
