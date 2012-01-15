var railways = (function($){
	
	// Standard response envelope
	amplify.request.decoders.envelope = function(data, status, xhr, success, error){
		if (xhr.status == 200 && data.status === "ok") {
			success(data.data);
		} else {
			msg = "Status code: " + xhr.status;
			if (data) msg = data.msg;
			alert("Error: " + msg);
		}
	};
	
	// Define AJAX requests
	amplify.request.define("lookup_postcode", "ajax", {
		url: siteurl + "/ajax/lookup_postcodes/{postcode}",
		type: "GET",
		decoder: "envelope"
	});
	
	amplify.request.define("lookup_locator", "ajax", {
		url: siteurl + "/ajax/lookup_locator/{locator}",
		type: "GET",
		decoder: "envelope"
	});
	
	var API = {};
	
	API.init = function(){
		
		// Attach events to elements
		
		$("#lookup_postcode").click(function(event){
			event.preventDefault();
			var pc = $("input#postcode").val();
			pc = pc.replace(/\s|\s+|\t|\r|\n/g, '');
			amplify.request({
				resourceId: "lookup_postcode",
				data: { postcode: pc },
				success: function(data){
					amplify.publish("found_bearings", { latlng: data.latlng });
					amplify.publish("found_locator", { locator: data.locator });
				}
			});
		});
		
		$("#lookup_locator").click(function(event){
			event.preventDefault();
			var loc = $("input#locator").val();
			loc = loc.replace(/\s|\s+\t|\r|\n/g, '');
			amplify.request({
				resourceId: "lookup_locator",
				data: { locator: loc },
				success: function(data){
					amplify.publish("found_bearings", { latlng: data.latlng });
				}
			})
		});
		
		$("input.latlng").change(function(){
			var latlng = [$("input#lat").val(), $("input#lng").val()].join(",");
			API.set_map(latlng);
		});
		
	};
	
	
	
	
	API.found_bearings = function(latlng){
		var coords = latlng;
		coords = coords.split(",");
		$("input[name=lat]").val(coords[0]);
		$("input[name=lng]").val(coords[1]);
	};
	
	
	
	API.found_locator = function(locator){
		$("input#locator").val(locator);
	}
	
	
	
	
	API.set_map = function(latlng){
		var mapdata = { coords: latlng, size: "460x200" };
		ROTA.staticmap(mapdata, function(data){
			$('<img>').appendTo("div#map").attr("src", data.src);
			$("div#map").show();
		});
	}
	
	
	
	amplify.subscribe("found_bearings", API.found_bearings);
	amplify.subscribe("location_changed", API.set_map);
	
	
	$(document).ready(API.init);
	
})(jQuery);