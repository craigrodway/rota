Array.prototype.max = function() {
  return Math.max.apply(null, this)
}

Array.prototype.min = function() {
  return Math.min.apply(null, this)
}

// Ensure all POST requests have the CSRF token
$.ajaxSetup({ data: { rota_csrf_token: $.cookie('rota_csrf_cookie') } });


jsq.add(function() {	
	
	// Attach events for tabs
	$("body").on("click", "dl.htabs > dd > a", function(e) {
		var location = $(this).attr("href");
		if (location.charAt(0) == "#") {
			e.preventDefault();
			$(this).closest("dl").find("a.selected").removeClass("selected");
			$(this).addClass("selected");
			$(location + "Tab").closest(".htabs-content").children("li").hide();
			$(location + "Tab").show();
		}
	});
	
	
	// "Delete" dialogs
	$("body").on("click", "a[rel='delete']", function(e) {
		
		e.preventDefault();
		e.stopImmediatePropagation();
		
		var el = $(this);
		
		// Get data attributes from element to allow the setting of information
		// displayed in the dialog.
		var data = el.data();
		
		$("#delete_dialog").modal({
			overlayClose: true,
			opacity: 80,
			minWidth: 500,
			maxWidth: 500,
			minHeight: 200,
			maxHeight: 320,
			onShow: function() {
				var container = $(this.d.container);
				//console.log(container);
				container.find("span.name").text(el.data("name"));
				container.find("p.text").html(el.data("text"));
				container.find("form#delete_form").attr("action", el.data("url"));
				container.find("form#delete_form input[name=id]").val(el.data("id")); 
			}
		});
	});
	
	$("a.close-dialog").on("click", function(e) {
		e.preventDefault();
		$.modal.close();
	});
	
});