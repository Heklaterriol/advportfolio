<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Field\Modal;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\Component\Advportfolio\Administrator\Helper\HtmlAdvportfolioHelper;

/**
 * Supports a modal article picker.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class ProjectField extends FormField
{
	/** @var	string	The form field type. */
	protected $type = 'Modal_Project';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput()
	{
		// Load the modal behavior script.
		HtmlAdvportfolioHelper::modal();

		// Build the script.
		$script = array();
		$script[] = '	function jSelectProject_' . $this->id . '(id, title, price) {';
		$script[] = '		jQuery("#' . $this->id . '_id").val(id);';
		$script[] = '		jQuery("#' . $this->id . '_name").val(title);';
		$script[] = '		jQuery.fancybox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		Factory::getApplication()->getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_advportfolio&amp;view=projects&amp;layout=modal&amp;tmpl=component&amp;function=jSelectProject_' . $this->id;

		if (isset($this->element['language'])) {
			$link .= '&amp;forcedLanguage='.$this->element['language'];
		}

		$db	= Factory::getContainer()->get('DatabaseDriver');
		$db->setQuery(
			'SELECT title' .
			' FROM #__advportfolio_projects' .
			' WHERE id = '.(int) $this->value
		);

		try {
			$title = $db->loadResult();
		} catch (\RuntimeException $e){
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		if (empty($title)) {
			$title = Text::_('COM_ADVPORTFOLIO_SELECT_A_PROJECT');
		}

		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current user display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" /><a data-fancybox-type="iframe" class="sl_modal btn" title="' . Text::_('COM_ADVPORTFOLIO_CHANGE_PROJECT') . '"  href="' . $link . '&amp;' . Session::getFormToken() . '=1"><i class="icon-file"></i> ' . Text::_('JSELECT') . '</a>';
		$html[] = '</span>';

		// The active article id field.
		if (0 == (int) $this->value) {
			$value = '';
		} else {
			$value = (int) $this->value;
		}

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
}