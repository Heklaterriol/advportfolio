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
use JoomlaCMSHelperContentHelper;
use JoomlaCMSLayoutLayoutHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_advportfolio.admin.script');
$wa->useStyle('com_advportfolio.admin.style');

ContentHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$user = Factory::getApplication()->getIdentity();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$archived = $this->state->get('filter.state') == 2 ? true : false;
$trashed = $this->state->get('filter.state') == -2 ? true : false;
$canOrder = $user->authorise('core.edit.state', 'com_advportfolio.category');
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_advportfolio&task=projects.saveOrderAjax&tmpl=component';
    $wa->useScript('sortablelist');
}

$sortFields = $this->getSortFields();

$wa->addInlineScript("Joomla.orderTable = function() { var table = document.getElementById('sortTable'); var direction = document.getElementById('directionTable'); var order = table.options[table.selectedIndex].value; var dirn = (order != '$listOrder') ? 'asc' : direction.options[direction.selectedIndex].value; var form = document.getElementById('adminForm'); form.filter_order.value = order; form.filter_order_Dir.value = dirn; form.submit(); }", []);
$wa->addInlineScript("Joomla.checkAll = function(element) { var form = element.form; var cbx = form.querySelectorAll('input[name^="cid"]'); for (var i = 0; i < cbx.length; i++) { cbx[i].checked = element.checked; } var boxchecked = form.querySelector('input[name="boxchecked"]'); if (boxchecked) { boxchecked.value = element.checked ? cbx.length : 0; } }", []);
?>
<form action="<?php echo Route::_('index.php?option=com_advportfolio&view=projects'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty($this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2"><?php echo $this->sidebar; ?></div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif; ?>
<div id="filter-bar" class="btn-toolbar">
<div class="filter-search btn-group pull-left">
<label for="filter_search" class="visually-hidden"><?php echo Text::_('JSEARCH_FILTER_LABEL'); ?></label>
<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo Text::_('COM_ADVPORTFOLIO_SEARCH_IN_TITLE'); ?>" placeholder="<?php echo Text::_('COM_ADVPORTFOLIO_SEARCH_IN_TITLE'); ?>" />
</div>
<div class="btn-group pull-left">
<button class="btn hasTooltip" type="submit" title="<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
<button class="btn hasTooltip" type="button" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
</div>
<div class="btn-group pull-right hidden-phone">
<label for="limit" class="visually-hidden"><?php echo Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
<?php echo $this->pagination->getLimitBox(); ?>
</div>
<div class="btn-group pull-right hidden-phone">
<label for="directionTable" class="visually-hidden"><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></label>
<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
<option value=""><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></option>
<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo Text::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo Text::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
</select>
</div>
<div class="btn-group pull-right">
<label for="sortTable" class="visually-hidden"><?php echo Text::_('JGLOBAL_SORT_BY'); ?></label>
<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable();">
<option value=""><?php echo Text::_('JGLOBAL_SORT_BY'); ?></option>
<?php echo LayoutHelper::render('joomla.html.select.options', ['options' => $sortFields, 'value' => 'value', 'text' => 'text', 'selected' => $listOrder]); ?>
</select>
</div>
</div>
<div class="clearfix"></div>
<table class="table table-striped" id="projectList">
<thead>
<tr>
<th width="1%" class="nowrap center hidden-phone"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => '<i class="icon-menu-2"></i>', 'field' => 'a.ordering', 'direction' => $listDirn, 'order' => $listOrder, 'default' => 'asc', 'tip' => 'JGRID_HEADING_ORDERING']); ?></th>
<th width="1%" class="hidden-phone"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
<th width="1%" style="min-width:55px" class="nowrap center"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JSTATUS', 'field' => 'a.state', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
<th class="title"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JGLOBAL_TITLE', 'field' => 'a.title', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
<th width="5%"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JGRID_HEADING_ACCESS', 'field' => 'a.access', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
<th width="10%"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JAUTHOR', 'field' => 'a.created_by', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
<th width="5%"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JDATE', 'field' => 'a.created', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
<th width="5%"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JGRID_HEADING_LANGUAGE', 'field' => 'a.language', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
<th width="1%"><?php echo LayoutHelper::render('joomla.html.grid.sort', ['title' => 'JGRID_HEADING_ID', 'field' => 'a.id', 'direction' => $listDirn, 'order' => $listOrder]); ?></th>
</tr>
</thead>
<tbody>
<?php foreach ($this->items as $i => $item) :
	$canCreate = $user->authorise('core.create', 'com_advportfolio.category.' . $item->catid);
	$canEdit = $user->authorise('core.edit', 'com_advportfolio.category.' . $item->catid);
	$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
	$canChange = $user->authorise('core.edit.state', 'com_advportfolio.category.' . $item->catid) && $canCheckin;
?>
<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
<td class="order nowrap center hidden-phone">
<?php if ($canChange) :
	$disableClassName = ''; $disabledLabel = '';
	if (!$saveOrder) : $disabledLabel = Text::_('JORDERINGDISABLED'); $disableClassName = ' inactive'; endif; ?>
<span class="sortable-handler hasTooltip<?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>"><i class="icon-menu"></i></span>
<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
<?php else : ?>
<span class="sortable-handler inactive"><i class="icon-menu"></i></span>
<?php endif; ?>
</td>
<td class="center hidden-phone"><?php echo LayoutHelper::render('joomla.html.grid.id', ['i' => $i, 'id' => $item->id]); ?></td>
<td class="center"><?php echo LayoutHelper::render('joomla.html.jgrid.published', ['value' => $item->state, 'i' => $i, 'prefix' => 'projects.', 'canChange' => $canChange]); ?></td>
<td class="nowrap has-context">
<div class="pull-left">
<?php if ($item->checked_out) : ?>
<?php echo LayoutHelper::render('joomla.html.jgrid.checkedout', ['i' => $i, 'editor' => $item->editor, 'time' => $item->checked_out_time, 'prefix' => 'projects.', 'canCheckin' => $canCheckin]); ?>
<?php endif; ?>
<?php if ($canEdit) : ?>
<a href="<?php echo Route::_('index.php?option=com_advportfolio&task=project.edit&id=' . (int) $item->id); ?>"><?php echo $this->escape($item->title); ?></a>
<?php else : ?>
<?php echo $this->escape($item->title); ?>
<?php endif; ?>
<span class="small"><?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></span>
<div class="small"><?php echo Text::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?></div>
</div>
</td>
<td class="small hidden-phone"><?php echo $this->escape($item->access_level); ?></td>
<td class="small hidden-phone"><?php echo $this->escape($item->author_name); ?></td>
<td class="nowrap small hidden-phone"><?php echo LayoutHelper::render('joomla.html.date', ['date' => $item->created, 'format' => Text::_('DATE_FORMAT_LC4')]); ?></td>
<td class="center nowrap">
<?php if ($item->language == '*') : ?>
<?php echo Text::alt('JALL', 'language'); ?>
<?php else : ?>
<?php echo $item->language_title ? $this->escape($item->language_title) : Text::_('JUNDEFINED'); ?>
<?php endif; ?>
</td>
<td class="center"><?php echo (int) $item->id; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php echo $this->pagination->getListFooter(); ?>
<?php echo $this->loadTemplate('batch'); ?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo LayoutHelper::render('joomla.html.form.token'); ?>
</form>
<?php echo AdvPortfolioFactory::getFooter();