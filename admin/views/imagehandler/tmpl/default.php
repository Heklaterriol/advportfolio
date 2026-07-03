<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('dropzone');
$wa->useStyle('dropzone');
?>
<div class="row-fluid">
	<div class="span12">
		<form action="<?php echo Uri::base(); ?>index.php?option=com_advportfolio&task=imagehandler.upload" method="post" enctype="multipart/form-data" class="dropzone" id="advPortfolioDropzone">
			<input type="hidden" name="<?php echo Factory::getSession()->getFormToken(); ?>" value="1" />
			<div class="dz-message">
				<span class="dz-message-text"><?php echo Text::_('COM_ADVPORTFOLIO_DROP_FILES_HERE'); ?></span>
				<span class="dz-message-or"><?php echo Text::_('JOR'); ?></span>
				<button type="button" class="dz-message-button btn btn-primary"><?php echo Text::_('JSELECT_FILES'); ?></button>
			</div>
		</form>
	</div>
</div>
<script>
Dropzone.options.advPortfolioDropzone = {
	paramName: 'file',
	maxFilesize: 2,
	acceptedFiles: 'image/*',
	addRemoveLinks: true,
	dictRemoveFile: 'Remove file',
	dictDefaultMessage: 'Drop files here or click to upload',
	dictFallbackMessage: 'Your browser does not support drag and drop file uploads.',
	init: function() {
		this.on('success', function(file, response) {
			if (response.success) {
				alert('Upload successful');
			} else {
				alert('Error: ' + response.message);
			}
		});
	}
};
</script>