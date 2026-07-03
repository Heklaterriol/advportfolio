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

// Load WebAssetManager
\$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
\$wa->useScript('com_advportfolio.admin.script');
\$wa->useStyle('com_advportfolio.admin.style');

// Add form behaviors
ContentHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
\$wa->useScript('form.validation');
\$wa->useScript('chosen.jquery');
\$wa->useScript('bootstrap.tooltip');

\$app = Factory::getApplication();
\$input = \$app->getInput();
\$user = \$app->getIdentity();

// Get the form
\$form = \$this->form;
\$item = \$this->item;
\$canDo = ContentHelper::getActions('com_advportfolio');
?>

<script>
Joomla.submitbutton = function(task) {
	if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form'))) {
		Joomla.submitform(task, document.getElementById('project-form'));
	} else {
		alert('<?php echo \$this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
}
</script>

<form action="<?php echo Route::_('index.php?option=com_advportfolio&layout=edit&id=' . (int) \$item->id); ?>" method="post" name="adminForm" id="project-form" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo Text::_('COM_ADVPORTFOLIO_PROJECT_DETAILS'); ?></legend>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('title'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('title'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('alias'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('alias'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('catid'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('catid'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('short_description'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('short_description'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('description'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('description'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('type'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('type'); ?>
					</div>
				</div>
				
				<div class="control-group" id="video-link-group" style="display: <?php echo (\$item->type == 1) ? 'block' : 'none'; ?>;">
					<div class="control-label">
						<?php echo \$form->getLabel('video_link'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('video_link'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('thumbnail'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('thumbnail'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('images'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('images'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('link'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('link'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('state'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('state'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('access'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('access'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('language'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('language'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('tags'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('tags'); ?>
					</div>
				</div>
				
				<?php echo LayoutHelper::render('joomla.edit.params', ['form' => \$form, 'data' => \$this->item]); ?>
				
				<?php if (\$canDo->get('core.admin')) : ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo \$form->getLabel('id'); ?>
					</div>
					<div class="controls">
						<?php echo \$form->getInput('id'); ?>
					</div>
				</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo LayoutHelper::render('joomla.html.form.token'); ?>
</form>

<?php
// Add script for type change handler
\$wa->addInlineScript("
document.addEventListener('DOMContentLoaded', function() {
	var typeField = document.getElementById('jform_type');
	var videoLinkGroup = document.getElementById('video-link-group');
	
	if (typeField && videoLinkGroup) {
		typeField.addEventListener('change', function() {
			videoLinkGroup.style.display = (this.value == 1) ? 'block' : 'none';
		});
	}
});
", []);
