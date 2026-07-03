<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Project List Controller Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class AdvPortfolioControllerProjects extends JControllerAdmin {
	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIO_PROJECTS';

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Project', $prefix = 'AdvPortfolioModel', $config = array('ignore_request' => true)) {
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
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model	= $this->getModel();

		// Save the ordering
		$return	= $model->saveorder($pks, $order);

		if ($return) {
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
}