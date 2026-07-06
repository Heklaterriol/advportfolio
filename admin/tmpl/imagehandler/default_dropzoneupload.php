<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// add css & js
HTMLHelper::_('stylesheet', 'com_advportfolio/dropzone.css', false, true, false, false, true);
HTMLHelper::_('script', 'com_advportfolio/dropzone.js', false, true, false, false, true);

$allowed_exts	= array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG');

?>
<script>
(function ($) {
	var allowed_exts	= $.parseJSON('<?php echo json_encode($allowed_exts); ?>');

	Dropzone.options.uploadForm = {
		paramName: 'image',
		accept: function (file, done) {
			var typeFile = file.name.substr((file.name.lastIndexOf('.') + 1));
			if (jQuery.inArray(typeFile, allowed_exts) == -1) {
				this.removeFile(file);
				return;
			} else {
				isAllow = true;
				done();
			}
		},

		complete: function() {
			if (this.filesQueue.length == 0 && this.filesProcessing.length == 0 && isAllow == true) {
				window.location = window.location.href;
			}
		}
	}
})(jQuery);
</script>

<form class="dropzone" action="<?php echo Route::_("index.php?option=com_advportfolio&view=imagehandler&tmpl=component&&image_id=$this->image_id&folder=$this->folder"); ?>" method="post" name="uploadForm" id="uploadForm" enctype="multipart/form-data">
	<div class="fallback">
		<input class="inputbox" name="image" id="image" type="file" size="40" />
		<button class="btn btn-primary" type="button" onclick="Joomla.submitform('imagehandler.upload');">
			<i class="icon-upload icon-white"></i> <?php echo Text::_('COM_ADVPORTFOLIO_UPLOAD_BTN_LABEL'); ?>
		</button>
		<p>
			<span class="muted small">
				<?php echo Text::_('COM_ADVPORTFOLIO_UPLOAD_VALID_FILE_TYPE'); ?>
				<em><?php echo implode(', ', explode(',', str_replace(' ', '', 'gif, jpg, png'))); ?></em>
			</span>
		</p>
	</div>

	<input type="hidden" name="task" value="imagehandler.ajaxUpload" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>