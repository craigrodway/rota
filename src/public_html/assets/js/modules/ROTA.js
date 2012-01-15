ROTA = {};

ROTA.lookup_postcode = function(pc, cb){
	var pc = pc.replace(/\s|\s+|\t|\r|\n/g, '');
	if (pc.length > 0){
		$.ajax({
			type: "GET",
			url: siteurl + "/ajax/lookup_postcode/" + pc + "?",
			success: cb
		});
	}	
};

ROTA.staticmap = function(data, cb){
	$.ajax({
		type: "GET",
		data: data,
		url: siteurl + "/ajax/staticmap",
		success: cb
	})
}