<div class="container" id="headline">
	<div class="sixteen columns">
		<br>
		<p class="big">The <strong>Railways on the Air</strong> event is not just 
		about amateur radio and making contacts - it&rsquo;s also about celebrating 
		the unique position railways hold in our national heritage.</p>
	</div>
</div>

<div id="map" style="height: 400px"></div>

<script>
jsq.add(function() {
	
	// Arrays of lat and lngs of railways
	var lats = [];
	var lngs = [];
	
	// Dimensions of containers to use remaining V-space for map
	var header_height = $("div#header").outerHeight(true) 
		+ $("div.nav").outerHeight() 
		+ $("div.subnav").outerHeight()
	 	+ $("div#headline").outerHeight();
	var window_height = $(window).height();
	$("#map").height(window_height - header_height + "px");
	
	// Base map tile layer
	var cloudmade = new L.TileLayer('http://{s}.tile.cloudmade.com/{key}/{style}/256/{z}/{x}/{y}.png', {
		attribution: '<a href="/about/credits">About the maps</a>.',	//'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery &copy; <a href="http://cloudmade.com">CloudMade</a>',
		maxZoom: 18,
		key: "<?php echo config_item('maps_key') ?>",
		style: "<?php echo config_item('maps_style') ?>"
	});
	
	// Load in bing layer for satellite
	var bing = new L.BingLayer("Anqm0F_JjIZvT0P3abS6KONpaBaKuTnITRrnYuiJCE0WOhH6ZbE4DzeT6brvKVR5");
	
	// Control layer for swapping base layers
	var layers_ctl = new L.Control.Layers({ "Satellite": bing, "Map": cloudmade });
	
	// Main map object
	var map = new L.Map('map');
	
	// Rough guesstimate of the centre of the UK
	var centre = new L.LatLng(53.891391, -1.845703);
	
	// Create the map!
	map.setView(centre, 6)
		.addLayer(cloudmade)
		.addControl(layers_ctl);
	
	// Get the railways
	$.getJSON(siteurl + 'ajax/railways_geojson', function(res) {
		
		// Add new GeoJSON layer
		L.geoJson(res, {
			onEachFeature: function (f, l) {
				console.log(f.properties.popupContent);
				if (f.properties && f.properties.popupContent) {
					l.bindPopup(f.properties.popupContent);
				}
			}
		}).addTo(map);
		
		// Loop through the coordinates in order to get min/max values
		$.each(res.features, function(i, j) {
			if (j.geometry.coordinates[1] !== 0) lats.push(j.geometry.coordinates[1]);
			if (j.geometry.coordinates[0] !== 0) lngs.push(j.geometry.coordinates[0]);
		});
		
		// Create a bounding area using the min/max values
		var southWest = new L.LatLng(lats.min(), lngs.min()),
			northEast = new L.LatLng(lats.max(), lngs.max()),
			bounds = new L.LatLngBounds(southWest, northEast);
		
		// Fit the map to the bounding area
		map.fitBounds(bounds);
	});
	
});
</script>