<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSHelperStringHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('jquery');
$wa->useScript('com_advportfolio.isotope');
$wa->useStyle('com_advportfolio.style');

$columns = $this->params->get('num_columns', 3);
$item_class = ' column-' . $columns;
$image_width = 1200 / $columns;

$wa->addInlineScript("
(function($) {
$(document).ready(function() {
var $container = $("#projects-wrapper");
if (typeof $.fn.masonry !== 'undefined') {
$container.masonry({itemSelector: '.isotope-item', columnWidth: '.isotope-item', percentPosition: true});
} else if (typeof $.fn.isotope !== 'undefined') {
$container.isotope({itemSelector: '.isotope-item'});
}
var $optionSets = $("#projects-filter .option-set"), $optionLinks = $optionSets.find("a");
$optionLinks.click(function () {
var $this = $(this);
$this.stop();
if ($this.hasClass("selected")) { return false; }
var $optionSet = $this.parents(".option-set");
$optionSet.find(".selected").removeClass("selected");
$this.addClass("selected");
var options = {}, key = $optionSet.attr("data-option-key"), value = $this.attr("data-option-value");
value = value === "false" ? false : value;
options[ key ] = value;
if (typeof $.fn.isotope !== 'undefined') {
$container.isotope(options);
} else if (typeof $.fn.masonry !== 'undefined') {
$container.masonry('layout');
}
return false;
});
});
}(jQuery));
", []);
?>
<div class="portfolio-list<?php echo $this->pageclass_sfx; ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
<?php endif; ?>
<div class="projects-filter" id="projects-filter">
	<ul class="option-set" data-option-key="filter">
		<li><a href="#filter" class="selected" data-option-value="*"><?php echo Text::_('COM_ADVPORTFOLIO_FILTER_ALL'); ?></a></li>
		<?php foreach ($this->tags as $tag) : ?>
		<li><a href="#filter" data-option-value=".<?php echo StringHelper::stringURLSafe($tag); ?>"><?php echo $tag; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<div class="clearfix projects-wrapper" id="projects-wrapper">
<?php foreach ($this->items as $item) :
	$link = AdvPortfolioHelperRoute::getProjectRoute($item->slug);
	$class = '';
	foreach ($item->tags as $tag) {
		$class .= ' ' . StringHelper::stringURLSafe($tag);
	}
?>
<div class="isotope-item project-<?php echo $item->id . $class . $item_class; ?>">
	<div class="project-img">
		<a href="<?php echo $link; ?>">
			<?php echo AdvPortfolioHelper::renderImage($item->thumbnail, $image_width, null, $this->escape($item->title)); ?>
			<div class="img-overlay"></div>
		</a>
	</div>
	<div class="project-item-meta">
		<h4>
			<a rel="bookmark" title="<?php echo Text::_('COM_ADVPORTFOLIO_PERMALINK_TO') . ' ' . $item->title; ?>" href="<?php echo $link; ?>">
				<?php echo $item->title; ?>
			</a>
		</h4>
		<?php if ($this->params->get('show_short_description', 1)) : ?>
			<?php echo $item->short_description; ?>
		<?php endif; ?>
	</div>
</div>
<?php endforeach; ?>
</div>
<?php if (($this->params->get('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
<div class="pagination">
	<?php if ($this->params->get('show_pagination_results', 1)) : ?>
	<p class="counter pull-right"><?php echo $this->pagination->getPagesCounter(); ?></p>
	<?php endif; ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php endif; ?>
</div>
<?php echo AdvPortfolioHelper::poweredBy();