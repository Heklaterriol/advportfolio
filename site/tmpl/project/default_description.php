<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

?>

<?php echo $this->item->description; ?>

<?php if ($this->item->link) : ?>
	<div class="project-link">
		<a class="btn btn-success" target="_blank" href="<?php echo $this->item->link; ?>">
			<?php echo Text::_('COM_ADVPORTFOLIO_LAUNCH_PROJECT'); ?>
		</a>
	</div>
<?php endif; ?>