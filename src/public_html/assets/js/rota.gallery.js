/**
 * Railways on the Air
 * Copyright (C) 2011 Craig A Rodway <craig.rodway@gmail.com>
 *
 * Licensed under the Open Software License version 3.0
 * 
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt. It is also available 
 * through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 */

(function ($) {
	
	
	function Gallery(el) {
		this.$el = $(el);
		this.$list = this.$el.find("ul");
		this.cur_img = 0;
		this.imgs = [];
		this.init();
	}
	
	
	Gallery.prototype.init = function() {
		var self = this;
		
		var i = 1;
		// Iterate through all the images
		$.each(this.$list.find("li > img"), function(idx, img) {
			// Add an index to the image array
			self.imgs.push(i);
			// Set this index as a data attr on the image
			$(img).attr("data-id", i);
			i++;
		});

		// Add control bar
		var control = $('<div class="gallery-control"></div>')
			.insertBefore(self.$list)
			.on("click", "a.nav", { self: this }, self.navigate);
		
		// Add direction nav to control bar
		$('<a href="#" class="nav" data-direction="prev">&lt; Prev</a> <div class="status"></div> <a href="#" class="nav" data-direction="next">Next &gt;</a>')
			.appendTo(control);
		
		// Set the markup of the status section of the control bar
		control.find("div.status").html('Image <span class="cur_img"></span> of <span class="img_total"></span>');
		
		// Handle to the counter text
		self.$cur_img = control.find("div.status > span.cur_img");
		self.$img_total = control.find("div.status > span.img_total");
		
		// Set the total text
		self.$img_total.text(self.imgs.length);
		
		// If there is only one image, hide the control bar
		if (i === 2) control.hide();
				
		// kick it off to show the first image
		self.navigate_to(1);
	}
	
	
	/**
	 * Handle the next/prev click event
	 */
	Gallery.prototype.navigate = function(e) {
		// prevent the click from doing anything else
		e.preventDefault();
		
		// get reference to gallery from event
		var self = e.data.self;
		
		// direction to go
		var dir = $(this).data("direction");
		
		if (dir == 'next') {
			// +1 to the current image index
			var next_img = self.cur_img += 1;
			// But if the next image is now more than number of items, go back to start
			if (next_img > self.imgs.length) next_img = 1;
		} else if (dir == 'prev') {
			// -1 from the current image index
			var next_img = self.cur_img +- 1;
			// But if the next image is now less than 1, go to the end
			if (next_img < 1) next_img = self.imgs.length;
		}
		
		// Navigate to the new index
		self.navigate_to(next_img);
	}
	
	
	/**
	 * Navigate to a specific image index
	 */
	Gallery.prototype.navigate_to = function(i) {
		var self = this;
		// Get the list item of the image
		var $li = self.$list.find("img[data-id='" + i + "']").parent("li");
		// Show the list item and hide the siblings
		$li.show().siblings().hide();
		// Update the current image index + status text
		self.cur_img = i;
		self.$cur_img.text(i);
	}
	

	$.fn.gallery = function() {
		return this.each(function() {
			new Gallery(this);
		});
	};
	
	
})(jQuery);