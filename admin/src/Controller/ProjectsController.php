<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Utilities\ArrayHelper;

/**
 * Project List Controller Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class ProjectsController extends AdminController
{
	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIO_PROJECTS';

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Project', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		$model	= parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 */
	public function saveOrderAjax()
	{
		$pks	= $this->input->post->get('cid', array(), 'array');
		$order	= $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model	= $this->getModel();

		// Save the ordering
		$return	= $model->saveorder($pks, $order);

		if ($return) {
			echo "1";
		}

		// Close the application
		Factory::getAppl