<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Supports an HTML select list of Projects.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class ProjectorderingField extends FormField
{
	/** @var string		The form field type. */
	protected $type	= 'ProjectOrdering';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html	= array();
		$attr	= '';

		// Initialize some field attributes.
		$attr	.= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr	.= ((string) $this->element['disabled']) == 'true' ? ' disabled="disabled"' : '';
		$attr	.= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize Javascript field attributes.
		$attr	.= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get some field values from the form.
		$projectId	= (int) $this->form->getValue('id');
		$categoryId		= (int) $this->form->getValue('catid');

		// Build the query for the ordering list.
		$query = 'SELECT ordering AS value, title AS text'
				. ' FROM #__advportfolio_projects'
				. ' WHERE catid = ' . (int) $categoryId
				. ' ORDER BY ordering'
		;

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true') {
			$html[]	= HTMLHelper::_('list.ordering', '', $query, trim($attr), $this->value, $projectId ? 0 : 1);
			$html[]	= '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '" />';
		} else {
			// Create a regular list.
			$html[]	= HTMLHelper::_('list.ordering', $this->name, $query, trim($attr), $this->value, $projectId ? 0 : 1);
		}

		return implode($html);
	}
}