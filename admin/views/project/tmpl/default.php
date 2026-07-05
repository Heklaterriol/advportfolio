<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Helper\ContentHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
wa->registerScript(
    'com_advportfolio.admin.script',
    'media/com_advportfolio/js/admin.script.js',
    [],
    ['defer' => true]
);

$wa->registerStyle(
    'com_advportfolio.admin.style',
    'media/com_advportfolio/css/admin.style.css'
);

ContentHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$wa->addInlineScript("Joomla.submitbutton = function(task, form) { if (task == 'project.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) { Joomla.submitform(task, form); } }", []);
?>
<form action="<?php echo Route::_('index.php?option=com_advportfolio&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo Text::_('COM_ADVPORTFOLIO_PROJECT_DETAILS'); ?></legend>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('short_description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('short_description'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('images'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('images'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('thumbnail'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('thumbnail'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('video_link'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('video_link'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('link'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('link'); ?></div>
				</div>
				<?php echo $this->form->getInput('id'); ?>
				<?php echo $this->form->getInput('created'); ?>
				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
				<?php echo $this->form->getInput('metakey'); ?>
				<?php echo $this->form->getInput('metadesc'); ?>
				<?php echo $this->form->getInput('metadata'); ?>
			</fieldset>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo LayoutHelper::render('joomla.html.form.token'); ?>
</form>