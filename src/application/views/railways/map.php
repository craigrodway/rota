<div class="container" id="headline">
	<div class="sixteen columns">
		<br>
		<p class="big">The <strong>Railways on the Air</strong> event is not just 
		about amateur radio and making contacts - it&rsquo;s also about celebrating 
		the unique position railways hold in our national heritage.</p>
	</div>
</div>

<div id="map" style="height: 200px"></div>

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
	
	// Set up GeoJSON object for holding the points
	var railways = new L.GeoJSON(null, {
		pointToLayer: function (latlng) {
			return new L.Marker(latlng);
		}
	});
	
	// Base map tile layer
	var cloudmade = new L.TileLayer('http://{s}.tile.cloudmade.com/1f5dfc0b37724a46b30912bd55c0a97c/67654/256/{z}/{x}/{y}.png', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery &copy; <a href="http://cloudmade.com">CloudMade</a>',
		maxZoom: 18
	});
	// Load in bing layer for satellite
	var bing = new L.BingLayer("Anqm0F_JjIZvT0P3abS6KONpaBaKuTnITRrnYuiJCE0WOhH6ZbE4DzeT6brvKVR5");
	// Control layer for swapping base layers
	var layers_ctl = new L.Control.Layers({ "Satellite": bing, "Map": cloudmade });
	
	// Main map object
	var map = new L.Map('map');
	
	// Rough guesstimate of the centre of the UK
	var centre = new L.LatLng(53.891391, -1.845703);
	
	// Popup windows
	railways.on("featureparse", function (e) {
		if (e.properties && e.properties.popupContent) {
			e.layer.bindPopup(e.properties.popupContent);
		}
	});
	
	// Create the map!
	map.setView(centre, 6)
		.addLayer(cloudmade)
		//.addLayer(bing)
		.addLayer(railways)
		.addControl(layers_ctl);
	
	// Get the railways
	$.getJSON(siteurl + 'ajax/railways_geojson', function(res) {
		
		// Send the GeoJSON data to the railways layer
		railways.addGeoJSON(res);
		
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