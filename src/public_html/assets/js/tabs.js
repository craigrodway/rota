// JS tabs library

var tabs = {};

tabs.init = function() {
	
	$("body").on("click", "dl.tabs > dd > a", function(e) {
		var location = $(this).attr("href");
		if (location.charAt(0) == "#") {
			e.preventDefault();
			$(this).closest("dl").find("a.active").removeClass("active");
			$(this).addClass("active");
			$(location + "Tab").closest(".tabs-content").children("li").hide();
			$(location + "Tab").show();
		}
		
	});
	
}

jsq.add(tabs.init);