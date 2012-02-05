var railway = (function($, amplify){
	
	
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
		url: siteurl + "/ajax/lookup_postcode/{postcode}",
		type: "GET",
		decoder: "envelope"
	});

	amplify.request.define("lookup_locator", "ajax", {
		url: siteurl + "/ajax/lookup_locator/{locator}",
		type: "GET",
		decoder: "envelope"
	});
	
	
	var init = function() {
		
		$("#lookup_postcode").click(function(event){
			
			event.preventDefault();
			var pc = $("input#postcode").val();
			pc = pc.replace(/\s|\s+|\t|\r|\n/g, '');
			
			amplify.request({
				resourceId: "lookup_postcode",
				data: { postcode: pc },
				success: function(data){
					amplify.publish("/railway/got_location", data);
				}
			});
			
		});
		
		
		$("#lookup_locator").click(function(event){
			
			event.preventDefault();
			var loc = $("input[name=r_locator]").val();
			loc = loc.replace(/\s|\s+\t|\r|\n/g, '');
			
			amplify.request({
				resourceId: "lookup_locator",
				data: { locator: loc },
				success: function(data){
					amplify.publish("/railway/got_location", data);
				}
			});
			
		});
		
		
		$("input.latlng").change(function(){
			
			var latlng = [$("input[name=r_lat]").val(), $("input[name=r_lng]").val()].join(",");
			amplify.publish("/railway/got_location", { latlng: latlng });
			
		});
		
	}
	
	
	// Got coords - do stuff
	var got_location = function(data) {
		
		if (data.locator) {
			$("input[name=r_locator]").val(data.locator);
		}
		
		var coords = data.latlng;
		coords = coords.split(",");
		$("input[name=r_lat]").val(coords[0]);
		$("input[name=r_lng]").val(coords[1]);
		
	}
	
	
	// Update the map
	var set_map = function(data) {
		
		if (data.latlng) {
			var mapdata = { coords: data.latlng, size: "460x200" };
			ROTA.staticmap(mapdata, function(data){
				console.log(data);
				var img = $('<img>').attr("src", data.src);
				$("div#map").empty().append(img);
			});
		}
		
	}
	
	
	// Add private init method to the page's javascript onready queue
	jsq.add(init);
	
	// Couple of pubsub subscriptions
	amplify.subscribe("/railway/got_location", got_location);
	amplify.subscribe("/railway/got_location", set_map);
	
	// No public methods to return
	return {}
	
	
})(jQuery, amplify);