<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
// Intentional cross-client reference: mirrors the original Joomla 3 design
// (see HtmlAdvportfolioHelper class docblock for details). Not an oversight.
use Joomla\Component\Advportfolio\Administrator\Helper\HtmlAdvportfolioHelper;

HtmlAdvportfolioHelper::modal();
HTMLHelper::_('script', 'com_advportfolio/jquery.flexslider.js', false, true);

$this->document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		$('.flexslider').flexslider({
			controlNav: false
		});
	});
})(jQuery);
");
?>

<div class="flexslider clearfix">
	<ul class="slides">
		<?php foreach ($this->item->images as $image) : ?>
			<li>
				<div class="project-img">
					<a rel="gallery" class="sl_modal" title="<?php echo $this->escape($image->title); ?>" href="<?php echo HtmlAdvportfolioHelper::image($image->image); ?>">
						<?php echo HtmlAdvportfolioHelper::image($image->image, null, null, $this->escape($image->title ? $image->title : $this->item->title)); ?>
						<div class="img-overlay"></div>
					</a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>