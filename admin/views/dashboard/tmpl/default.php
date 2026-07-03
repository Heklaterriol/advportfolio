<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSRouterRoute;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('com_advportfolio.admin.style');
?>
<div class="row-fluid">
	<div class="span12">
		<div class="alert alert-info">
			<h4 class="alert-heading"><?php echo Text::_('COM_ADVPORTFOLIO_DASHBOARD_WELCOME'); ?></h4>
			<div class="alert-message"><?php echo Text::_('COM_ADVPORTFOLIO_DASHBOARD_DESCRIPTION'); ?></div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span6">
		<div class="card">
			<div class="card-header"><h3><?php echo Text::_('COM_ADVPORTFOLIO_QUICK_ACTIONS'); ?></h3></div>
			<div class="card-body">
				<ul class="nav nav-list">
					<li><a href="<?php echo Route::_('index.php?option=com_advportfolio&task=project.add'); ?>"><i class="icon-plus"></i> <?php echo Text::_('COM_ADVPORTFOLIO_ADD_PROJECT'); ?></a></li>
					<li><a href="<?php echo Route::_('index.php?option=com_categories&extension=com_advportfolio'); ?>"><i class="icon-folder-open"></i> <?php echo Text::_('COM_ADVPORTFOLIO_MANAGE_CATEGORIES'); ?></a></li>
					<li><a href="<?php echo Route::_('index.php?option=com_advportfolio&view=projects'); ?>"><i class="icon-list"></i> <?php echo Text::_('COM_ADVPORTFOLIO_VIEW_PROJECTS'); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="card">
			<div class="card-header"><h3><?php echo Text::_('COM_ADVPORTFOLIO_STATISTICS'); ?></h3></div>
			<div class="card-body">
				<dl class="dl-horizontal">
					<dt><?php echo Text::_('COM_ADVPORTFOLIO_TOTAL_PROJECTS'); ?>:</dt>
					<dd><?php echo (int) $this->totalProjects; ?></dd>
					<dt><?php echo Text::_('COM_ADVPORTFOLIO_TOTAL_CATEGORIES'); ?>:</dt>
					<dd><?php echo (int) $this->totalCategories; ?></dd>
					<dt><?php echo Text::_('COM_ADVPORTFOLIO_PUBLISHED_PROJECTS'); ?>:</dt>
					<dd><?php echo (int) $this->publishedProjects; ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>