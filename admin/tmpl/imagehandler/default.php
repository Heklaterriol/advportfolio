<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Advportfolio\Administrator\Helper\AdvportfolioHelper;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('formbehavior.chosen', 'select');
?>

<style>
	body {
		padding-top: 0;
	}
</style>

<?php echo $this->loadTemplate('dropzoneupload'); ?>

<form action="<?php echo Route::_("index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=$this->image_id&folder=$this->folder"); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="subhead clearfix">
		<div class="container-fluid search-form pull-left">
			<div class="input-append pull-left" style="margin-right: 20px;">
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo Text::_('COM_ADVPORTFOLIO_SEARCH_IN_TITLE'); ?>" />
				<button type="submit" class="btn">
					<i class="icon-search"></i>
				</button>
				<button type="button" class="btn" onclick="document.id('filter_search').value = ''; this.form.submit();">
					<i class="icon-remove"></i>
				</button>
			</div>

			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ;?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</div>

		<div class="pull-right">
			<div class="input-append">
				<input type="text" name="new_folder" id="new_folder" class="input-medium" value="" />
				<button type="button" class="btn" onclick="if (this.form.new_folder.value) { this.form.task.value = 'imagehandler.createFolder'; this.form.submit(); }">
					<?php echo Text::_('COM_ADVPORTFOLIO_FOLDER_CREATE'); ?>
				</button>
			</div>
		</div>
	</div>

	<ul class="manager thumbnails" style="padding-left: 20px;">
		<?php if ($this->folder) : ?>
			<li class="imgOutline thumbnail width-90 center">
				<div align="center" class="imageborder">
					<a href="<?php echo Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $this->image_id . '&folder=' . dirname($this->folder)); ?>">
						<?php echo HTMLHelper::_('image', 'com_advportfolio/folder.png', 'folder', '', true); ?>
					</a>
				</div>
				<div class="imagecontrol">

				</div>
				<div class="imageinfo">
					..
				</div>
			</li>
		<?php endif; ?>

		<?php foreach ($this->folders as $folder) : ?>
			<li class="imgOutline thumbnail width-90 center">
				<div align="center" class="imageborder">
					<a href="<?php echo Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $this->image_id . '&folder=' . ($this->folder ? $this->folder . '/' : '') . $folder->name); ?>" title="<?php echo $folder->name; ?>">
						<?php echo HTMLHelper::_('image', 'com_advportfolio/folder.png', $folder->name, '', true); ?>
					</a>
				</div>
				<div class="imagecontrol">

				</div>
				<div class="imageinfo">
					<?php echo $this->escape(strlen($folder->name) > 13 ? substr($folder->name, 0, 10) . '...' : $folder->name); ?>
				</div>
			</li>
		<?php endforeach; ?>

		<?php for ($i = 0, $n = count($this->items); $i < $n; $i++) : ?>
			<?php $this->setImage($i); ?>
			<?php echo $this->loadTemplate('image'); ?>
		<?php endfor; ?>
	</ul>

	<?php if ($this->pagination->total > $this->pagination->limit) : ?>
	<?php echo $this->pagination->getListFooter(); ?>
	<?php endif; ?>

	<input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php
echo AdvportfolioHelper::getFooter();