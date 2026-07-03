/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if (!Skyline) {
	var Skyline = {};
}

Skyline.AdvPortfolio = {
	/**
	 * Image.
	 */
	image: {
		count:			0,
		initialized:	false,

		/**
		 * Initialize.
		 */
		init: function() {
			if (Skyline.AdvPortfolio.image.initialized) {
				return;
			}

			Skyline.AdvPortfolio.image.initialized = true;

			jQuery('.image-clear').click(function() {
				Skyline.AdvPortfolio.image.clear(this);
			});
		},

		/**
		 * Select image from modal.
		 */
		select: function(id, imageName, preview) {
			parent.jQuery.fancybox.close();
			$el	= jQuery('#' + id)
			if ($el.val() != imageName) {
				if ($el.val() == '' && $el.parents('#jform_images_container').length) {
					Skyline.AdvPortfolio.image.add();
				}

				$el.val(imageName);
				$el.siblings('.image-preview').hide().html('<img src="' + preview + '" alt="' + imageName + '" class="img-polaroid" />').show('slide');
			}
		},

		initList: function() {
			Skyline.AdvPortfolio.image.init();
			Skyline.AdvPortfolio.image.add();

			jQuery('.images-container').sortable({
				'handle':	'.image-sortable'
			});
		},

		refresh: function() {
			jQuery('#jform_images_container .image-clear').unbind('click').click(function() {
				if (jQuery('#jform_images_container > li').length > 1 && jQuery(this).siblings('.image-input').val() != '') {
					jQuery(this).parents('li').hide('slide', function() {
						jQuery(this).remove();
					});
				} else {
					Skyline.AdvPortfolio.image.clear(this);
				}
			});
		},

		/**
		 * Add an image.
		 */
		add: function() {
			var id = Skyline.AdvPortfolio.image.count++;

			jQuery('<li><div class="image-sortable"></div><div class="image-container"><input type="hidden" id="jform_images_' + id + '_image" name="jform[images][image][]" class="image-input" /><a href="index.php?option=com_advportfolio&amp;view=imagehandler&amp;tmpl=component&amp;image_id=jform_images_' + id + '_image" data-fancybox-type="iframe" class="sl_modal image-select btn"><i class="icon-pictures"></i> Select</a> <a href="javascript:void(0);" class="btn image-clear"><i class="icon-remove"></i> Clear</a><div class="image-title"><input type="text" name="jform[images][title][]" placeholder="Image Title" /></div><div class="image-preview" style="display:none;"></div></div></li>').appendTo('#jform_images_container').hide().show('slide');
			Skyline.AdvPortfolio.image.refresh();
		},

		/**
		 * Clear image.
		 */
		clear: function(id) {
			$id = jQuery(id);
			$id.siblings('.image-input').val('');
			$id.siblings('.image-title').children('input').val('');
			$id.siblings('.image-preview').hide('slide', function() {
				jQuery(this).html('');
			});
		}
	}
};