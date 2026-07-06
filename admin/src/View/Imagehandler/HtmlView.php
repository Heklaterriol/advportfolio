<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\View\Imagehandler;

defined('_JEXEC') or die;

use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Object\CMSObject;

/**
 * View class for a list of images.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view.
	 */
	public function display($tpl = null)
	{
		$app				= Factory::getApplication();
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->folders		= $this->get('folders');
		$this->folder		= $this->state->get('folder');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors), 500);
		}

		$this->image_id		= $app->getInput()->getCmd('image_id');
		$this->require_ftp	= !ClientHelper::hasCredentials('ftp');

		parent::display($tpl);
	}

	function setImage($index = 0)
	{
		if (isset($this->items[$index])) {
			$this->_tmp_img = &$this->items[$index];
		} else {
			$this->_tmp_img = new CMSObject;
		}
	}
}