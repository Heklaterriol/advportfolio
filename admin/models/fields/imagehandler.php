<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Provides a modal image selector including upload mechanism.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class JFormFieldImageHandler extends JFormField {

	/** @var string		The form field type. */
	protected $type	= 'ImageHandler';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput() {
		JHtml::_('advportfolio.modal');
		JHtml::_('script', 'com_advportfolio/admin.script.js', array(), true);
		JHtml::_('stylesheet', 'com_advportfolio/admin.style.css', array(), true);

		static $js;
		$document	= JFactory::getDocument();

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
			. ($this->element['readonly'] ? '' : JRoute::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $this->id))
			. '"><i class="icon-pictures"></i> ' . JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';

		$html[] = '<a class="btn image-clear"'
			. ' href="javascript:void(0);"><i class="icon-remove"></i> ' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		$html[] = '<div class="image-preview">';
		$html[]	= $this->value ? JHtml::_('advportfolio.image', $this->value, 200, 200, $this->value, 100, false, 'class="img-polaroid"') : '';
		$html[]	= '</div>';

		return implode("\n", $html);
	}
}