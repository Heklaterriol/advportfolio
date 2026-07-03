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
	if (typeof $.fn.masonry === 'undefined' && typeof $.fn.isotope === 'undefined') {
		console.warn('Neither Masonry nor Isotope is available');
		return;
	}
	$(document).ready(function() {
		var $container = $('#projects-wrapper');
		if (typeof $.fn.masonry !== 'undefined') {
			$container.masonry({itemSelector: '.isotope-item', columnWidth: '.isotope-item', percentPosition: true});
		} else if (typeof $.fn.isotope !== 'undefined') {
			$container.isotope({itemSelector: '.isotope-item'});
		}
		var $optionSets = $('#projects-filter .option-set'), $optionLinks = $optionSets.find('a');
		$optionLinks.on('click', function() {
			var $this = $(this);
			$this.stop();
			if ($this.hasClass('selected')) { return false; }
			var $optionSet = $this.parents('.option-set');
			$optionSet.find('.selected').removeClass('selected');
			$this.addClass('selected');
			var options = {}, key = $optionSet.attr('data-option-key'), value = $this.attr('data-option-value');
			value = value === 'false' ? false : value;
			options[ key ] = value;
			if (typeof $.fn.isotope !== 'undefined') {
				$container.isotope(options);
			} else if (typeof $.fn.masonry !== 'undefined') {
				$container.masonry('layout');
			}
			return false;
		});
		if (typeof $.fn.isotope !== 'undefined') {
			$container.isotope();
		}
	});
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