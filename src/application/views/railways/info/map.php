<div id="map" style="height: 125px; border-bottom: 4px solid #2093C2"></div>

<script>
jsq.add(function() {
	
	var $map = $("div#map");
	var map_expanded = false;
	
	// Toggle the map state
	toggle_map = function() {
		if ( ! map_expanded) {
			// Reset view of map
			map.invalidateSize().zoomIn();
			// scroll page to nav is at the top to show more map
			$("html, body").animate({ scrollTop: $("div.nav").offset().top });
			// set status
			map_expanded = true;
			// reset cursor on map
			$map.css("cursor", "default");
		} else if (map_expanded) {
			// reset view
			map.invalidateSize().zoomOut();
			map_expanded = false;
			$map.css("cursor", "pointer");
		}
	}
	
	
	// Base map tile layer
	var cloudmade = new L.TileLayer('http://{s}.tile.cloudmade.com/{key}/{style}/256/{z}/{x}/{y}.png', {
		attribution: '',	/*'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery &copy; <a href="http://cloudmade.com">CloudMade</a>',*/
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
	var centre = new L.LatLng(<?php echo $railway->r_lat() ?>, <?php echo $railway->r_lng() ?>);
	
	var marker = new L.Marker(centre);
	marker.bindPopup("<?php echo stripslashes($railway->r_name()) ?>").openPopup();
	
	// Create the map!
	map.setView(centre, 13)
		.addLayer(cloudmade)
		.addLayer(marker)
		.addControl(layers_ctl);
	
	// Map size toggling
	$("a#map_toggle").toggle(function() {
		$("#map").animate({ height: ($(window).height() / 1.3) + "px"}, 250, function() {
			toggle_map();
		});
	}, function() {
		$("#map").animate({ height: "125px" }, 250, function() {
			toggle_map();
		});
	});
	
	// Handle clicking on the map when it's in the minified state.
	$("#map").on("click", function() {
		if ( ! map_expanded) $("a#map_toggle").trigger("click");
	}).css("cursor", "pointer");
	
});
</script>