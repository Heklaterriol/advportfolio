<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('stylesheet', 'com_advportfolio/style.css', false, true);

$hasMedia	= ($this->item->type == 0 && count($this->item->images)) || ($this->item->type == 1 && $this->item->video_link);
$hasContent	= $this->item->description != '' && $this->params->def('show_description', 1);
$this->params->def('description_position', 'right');
$this->params->def('description_width', '4');
?>

<div class="item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>

			<?php if (!$this->params->get('show_title')) : ?>
				<div class="project-nav">
					<a class="prev-project<?php echo $this->prevItem ? '' : ' disable'; ?>" href="<?php echo $this->prevItem ? AdvPortfolioHelperRoute::getProjectRoute($this->prevItem->slug) : 'javascript:void(0);'; ?>"></a>
					<a class="next-project<?php echo $this->nextItem ? '' : ' disable'; ?>" href="<?php echo $this->nextItem ? AdvPortfolioHelperRoute::getProjectRoute($this->nextItem->slug) : 'javascript:void(0);'; ?>"></a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_title', true)) : ?>
	<div class="page-header">
		<h2 itemprop="name">
			<?php echo $this->escape($this->item->title); ?>
		</h2>

		<div class="project-nav">
			<a class="prev-project<?php echo $this->prevItem ? '' : ' disable'; ?>" href="<?php echo $this->prevItem ? AdvPortfolioHelperRoute::getProjectRoute($this->prevItem->slug) : 'javascript:void(0);'; ?>"></a>
			<a class="next-project<?php echo $this->nextItem ? '' : ' disable'; ?>" href="<?php echo $this->nextItem ? AdvPortfolioHelperRoute::getProjectRoute($this->nextItem->slug) : 'javascript:void(0);'; ?>"></a>
		</div>
	</div>
	<?php endif; ?>

	<div id="project-wrapper" class="project-wrapper clearfix">
		<?php if ($hasMedia && $hasContent) : ?>

			<?php if ($this->params->get('description_position') == 'top' || $this->params->get('description_position') == 'bottom') : ?>
				<?php if ($this->params->get('description_position') == 'top') : ?>
					<div>
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

				<div>
					<?php echo $this->loadTemplate('media'); ?>
				</div>

				<?php if ($this->params->get('description_position') == 'bottom') : ?>
					<div>
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

			<?php else : ?>

				<?php if ($this->params->get('description_position') == 'left') : ?>
					<div class="span<?php echo $this->params->get('description_width'); ?>">
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

				<div class="span<?php echo (12 - $this->params->get('description_width')); ?>">
					<?php echo $this->loadTemplate('media'); ?>
				</div>

				<?php if ($this->params->get('description_position') == 'right') : ?>
					<div class="span<?php echo $this->params->get('description_width'); ?>">
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

			<?php endif; ?>

		<?php else : ?>

			<?php if ($hasMedia) : ?>
				<div>
					<?php echo $this->loadTemplate('media'); ?>
				</div>
			<?php else : ?>
				<?php echo $this->loadTemplate('description'); ?>
			<?php endif; ?>

		<?php endif; ?>
	</div>
</div>

<?php echo AdvPortfolioHelper::poweredBy();