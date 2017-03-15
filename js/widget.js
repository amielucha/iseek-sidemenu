(function ($) {
	"use strict";
	$(function () {

		// Add ID to the collapsible subnav basing on the parent's target
		$('.iseek-sidemenu-class .children').each(function(index, el) {
			var id = $(el).parent().find('.navbar-toggler').attr('aria-controls');
			$(el).attr('id', id);
		});

		// Expand the collapsed subnav if it has current nav item in it
		$('.iseek-sidemenu-class .current-cat-parent > .navbar-toggler').removeClass('collapsed').next('.collapse-wrapper').addClass('in');

	});
}(jQuery));
