var ROTA = (function($, amplify){
	
	
	var staticmap = function(data, cb){
		$.ajax({
			type: "GET",
			data: data,
			url: siteurl + "/ajax/staticmap",
			success: cb
		})
	}
	
	
	return {
		staticmap: staticmap
	}
	
	
})(jQuery, amplify);