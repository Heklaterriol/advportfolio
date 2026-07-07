<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$published = $this->state->get('filter.state');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
		<h3><?php echo Text::_('COM_ADVPORTFOLIO_PROJECTS_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo Text::_('COM_ADVPORTFOLIO_PROJECTS_BATCH_TIP'); ?></p>
		<div class="control-group">
			<div class="controls">
				<?php echo LayoutHelper::render('joomla.html.batch.access', []); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<?php echo LayoutHelper::render('joomla.html.batch.language', []); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<?php echo LayoutHelper::render('joomla.html.batch.tag', []);?>
			</div>
		</div>
		<?php if ($published >= 0) : ?>
		<div class="control-group">
			<div class="controls">
				<?php echo LayoutHelper::render('joomla.html.batch.item', ['extension' => 'com_advportfolio']);?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch-category-id').value=''; document.id('batch-access').value=''; document.id('batch-language-id').value='';document.id('batch-tag-id)').value=''" data-dismiss="modal">
			<?php echo Text::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('project.batch');">
			<?php echo Text::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>