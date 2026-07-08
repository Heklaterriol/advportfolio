<?php
/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Advportfolio\Administrator\Helper\AdvportfolioHelper;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.formvalidator');
$this->document->addScriptDeclaration(
	<<<SCRIPT
	(function($) {
	$(document).ready(function() {
		$('label[for="jform_type0"]').click(function() {
			$('#images-container').show('slide');
			$('#video_link-container').hide('slide');
		});

		$('label[for="jform_type1"]').click(function() {
			$('#images-container').hide('slide');
			$('#video_link-container').show('slide');
		});
	});
})(jQuery);
SCRIPT
);
?>

	<script type="text/javascript">
		Joomla.submitbutton = function (task) {
			if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form'))) {
				Joomla.submitform(task, document.getElementById('project-form'));
			} else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	</script>

	<form action="<?php echo Route::_('index.php?option=com_advportfolio&layout=edit&id=' . (int)$this->item->id); ?>"
		  method="post" name="adminForm" id="project-form" class="form-validate">
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset>

					<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

					<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? Text::_('COM_ADVPORTFOLIO_PROJECT_NEW') : Text::sprintf('COM_ADVPORTFOLIO_PROJECT_EDIT', $this->item->id)); ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('link'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('link'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('short_description'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('short_description'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
					</div>
					<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

					<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'media', Text::_('COM_ADVPORTFOLIO_PROJECT_FIELDSET_MEDIA', true)); ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('thumbnail'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('thumbnail'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
					</div>
					<div class="control-group"
						 id="images-container"<?php echo $this->item->type == 1 ? 'style="display: none;"' : ''; ?>>
						<div class="control-label"><?php echo $this->form->getLabel('images'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('images'); ?></div>
					</div>
					<div class="control-group"
						 id="video_link-container"<?php echo $this->item->type == 0 ? 'style="display: none;"' : ''; ?>>
						<div class="control-label"><?php echo $this->form->getLabel('video_link'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('video_link'); ?></div>
					</div>
					<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

					<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('created_by_alias'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
					</div>
					<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

					<?php $fieldSets = $this->form->getFieldsets('params'); ?>
					<?php foreach ($fieldSets as $name => $fieldSet) : ?>
						<?php $paramstabs = 'params-' . $name; ?>
						<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', $paramstabs, Text::_($fieldSet->label, true)); ?>
						<?php echo $this->loadTemplate('params'); ?>
						<?php echo HTMLHelper::_('bootstrap.endTab'); ?>
					<?php endforeach; ?>

					<?php $fieldSets = $this->form->getFieldsets('metadata'); ?>
					<?php foreach ($fieldSets as $name => $fieldSet) : ?>
						<?php $metadatatabs = 'metadata-' . $name; ?>
						<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', $metadatatabs, Text::_($fieldSet->label, true)); ?>
						<?php echo $this->loadTemplate('metadata'); ?>
						<?php echo HTMLHelper::_('bootstrap.endTab'); ?>
					<?php endforeach; ?>

					<input type="hidden" name="task" value=""/>
					<input type="hidden" name="return"
						   value="<?php echo Factory::getApplication()->getInput()->getCmd('return'); ?>"/>
					<?php echo HTMLHelper::_('form.token'); ?>


					<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>
				</fieldset>
			</div>
			<?php echo LayoutHelper::render('joomla.edit.details', $this); ?>
		</div>
	</form>

<?php
echo AdvportfolioHelper::getFooter();