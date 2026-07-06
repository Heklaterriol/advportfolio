<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Advportfolio\Administrator\Helper\AdvportfolioHelper;

$xml	= simplexml_load_file(JPATH_ROOT . '/administrator/components/com_advportfolio/advportfolio.xml');
?>
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
	<div class="span6">
		<div class="well well-small">
			<div class="module-title nav-header">
				<?php echo Text::_('COM_ADVPORTFOLIO_SUBMENU_DASHBOARD'); ?>
			</div>
			<div class="row-striped">
				<div id="cpanel">
					<?php
					$this->_quickIcon('index.php?option=com_advportfolio&view=projects', 'icon-64-projects.png', 'COM_ADVPORTFOLIO_SUBMENU_PROJECTS');
					$this->_quickIcon('index.php?option=com_categories&extension=com_advportfolio', 'icon-64-categories.png', 'COM_ADVPORTFOLIO_SUBMENU_CATEGORIES');
//					$this->_quickIcon('index.php?option=com_advportfolio&view=tags', 'icon-64-tags.png', 'COM_ADVPORTFOLIO_SUBMENU_TAGS');
					$this->_quickIcon('index.php?option=com_config&view=component&component=com_advportfolio&return=' . urlencode(base64_encode(Uri::getInstance())), 'icon-64-config.png', 'COM_ADVPORTFOLIO_SUBMENU_CONFIG');
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="span6">
		<?php echo $xml->description; ?>
	</div>
</div>

<?php
echo AdvportfolioHelper::getFooter();