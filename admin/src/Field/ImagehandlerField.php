<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Advportfolio\Administrator\Helper\HtmlAdvportfolioHelper;

/**
 * Provides a modal image selector including upload mechanism.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class ImagehandlerField extends FormField
{
	/** @var string		The form field type. */
	protected $type	= 'ImageHandler';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput()
	{
		HtmlAdvportfolioHelper::modal();
		HTMLHelper::_('script', 'com_advportfolio/admin.script.js', array(), true);
		HTMLHelper::_('stylesheet', 'com_advportfolio/admin.style.css', array(), true);

		static $js;
		$document	= Factory::getApplication()->getDocument();

		if (!$js) {
			$js = true;
			$document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		Skyline.AdvPortfolio.image.init();
	});
})(jQuery);
");
		}

		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// The text field.
		$html[] = '<input type="hidden" class="image-input" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $attr . ' />';

		// The button.
		$html[] = '<a class="sl_modal image-select btn" data-fancybox-type="iframe" href="'
			. ($this->element['readonly'] ? '' : Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $this->id))
			. '"><i class="icon-pictures"></i> ' . Text::_('JLIB_FORM_BUTTON_SELECT') . '</a>';

		$html[] = '<a class="btn image-clear"'
			. ' href="javascript:void(0);"><i class="icon-remove"></i> ' . Text::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		$html[] = '<div class="image-preview">';
		$html[]	= $this->value ? HtmlAdvportfolioHelper::image($this->value, 200, 200, $this->value, 100, false, 'class="img-polaroid"') : '';
		$html[]	= '</div>';

		return implode("\n", $html);
	}
}