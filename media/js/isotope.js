(function(factory) {
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (typeof module === 'object' && typeof module.exports === 'object') {
		module.exports = factory(require('jquery'));
	} else {
		factory(jQuery);
	}
}(function($) {
	'use strict';

	// Check if Masonry is available (Joomla Core)
	if (typeof $.fn.masonry === 'undefined') {
		// Fallback to Isotope if available
		if (typeof $.fn.isotope === 'undefined') {
			console.warn('Neither Masonry nor Isotope is available');
			return;
		}
	}

	// Initialize portfolio grid
	$(document).ready(function() {
		var $container = $('#projects-wrapper');
		
		if (typeof $.fn.masonry !== 'undefined') {
			// Use Masonry (Joomla Core)
			$container.masonry({
				itemSelector: '.isotope-item',
				columnWidth: '.isotope-item',
				percentPosition: true
			});
		} else if (typeof $.fn.isotope !== 'undefined') {
			// Fallback to Isotope
			$container.isotope({
				itemSelector: '.isotope-item'
			});
		}
		
		// Handle filter clicks
		var $optionSets = $('#projects-filter .option-set'),
			$optionLinks = $optionSets.find('a');

		$optionLinks.on('click', function() {
			var $this = $(this);
			
			// Don't proceed if already selected
			if ($this.hasClass('selected')) {
				return false;
			}
			
			var $optionSet = $this.parents('.option-set');
			$optionSet.find('.selected').removeClass('selected');
			$this.addClass('selected');

			// Make option object dynamically
			var options = {},
				key = $optionSet.attr('data-option-key'),
				value = $this.attr('data-option-value');
			
			// Parse "false" as false boolean
			value = value === 'false' ? false : value;
			options[key] = value;

			// Apply new options
			if (typeof $.fn.isotope !== 'undefined') {
				$container.isotope(options);
			} else if (typeof $.fn.masonry !== 'undefined') {
				$container.masonry('layout');
			}

			return false;
		});
	});

	// Expose to global scope for compatibility
	window.AdvPortfolioIsotope = {
		init: function(container, options) {
			if (typeof $.fn.masonry !== 'undefined') {
				$(container).masonry(options);
			} else if (typeof $.fn.isotope !== 'undefined') {
				$(container).isotope(options);
			}
		}
	};
}));