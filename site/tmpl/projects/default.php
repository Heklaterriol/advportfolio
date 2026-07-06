<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Component\Advportfolio\Administrator\Helper\HtmlAdvportfolioHelper;
use Joomla\Component\Advportfolio\Site\Helper\AdvportfolioHelper;
use Joomla\Component\Advportfolio\Site\Service\Router;

// Include CSS and JS
HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'com_advportfolio/jquery.isotope.js', false, true);
HTMLHelper::_('stylesheet', 'com_advportfolio/style.css', false, true);

$columns		= $this->params->def('num_columns', 3);
$item_class		= ' column-' . $columns;
$image_width	= 1200 / $columns;

$this->document->addScriptDeclaration('
(function($) {
	$(window).load(function() {
		var $container = $("#projects-wrapper");

		$container.isotope({
			itemSelector:".isotope-item"
		});

		var $optionSets = $("#projects-filter .option-set"),
			$optionLinks = $optionSets.find("a");

		$optionLinks.click(function () {
			var $this = $(this);
			$this.stop();
			// don"t proceed if already selected
			if ($this.hasClass("selected")) {
				return false;
			}
			var $optionSet = $this.parents(".option-set");
			$optionSet.find(".selected").removeClass("selected");
			$this.addClass("selected");

			// make option object dynamically, i.e. { filter: ".my-filter-class" }
			var options = {},
					key = $optionSet.attr("data-option-key"),
					value = $this.attr("data-option-value");
			// parse "false" as false boolean
			value = value === "false" ? false : value;
			options[ key ] = value;

			// otherwise, apply new options
			$container.isotope(options);

			return false;
		});
	});
})(jQuery);
');
?>

<div class="portfolio-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>

	<div class="projects-filter" id="projects-filter">
		<ul class="option-set" data-option-key="filter">
			<li>
				<a href="#filter" class="selected" data-option-value="*">
					<?php echo Text::_('COM_ADVPORTFOLIO_FILTER_ALL'); ?>
				</a>
			</li>
			<?php foreach ($this->tags as $tag) : ?>
			<li>
				<a href="#filter" data-option-value=".<?php echo ApplicationHelper::stringURLSafe($tag); ?>"><?php echo $tag; ?></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="clearfix projects-wrapper" id="projects-wrapper">
		<?php foreach ($this->items as $item) :
			$link	= Router::getProjectRoute($item->slug);
			$class	= '';
			foreach ($item->tags as $tag) {
				$class .= ' ' . ApplicationHelper::stringURLSafe($tag);
			}
		?>

		<div class="isotope-item project-<?php echo $item->id . $class . $item_class; ?>">
			<div class="project-img">
				<a href="<?php echo $link; ?>">
					<?php echo HtmlAdvportfolioHelper::image($item->thumbnail, $image_width, null, $this->escape($item->title)); ?>
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

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter pull-right"><?php echo $this->pagination->getPagesCounter(); ?></p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php  endif; ?>
</div>

<?php echo AdvportfolioHelper::poweredBy();