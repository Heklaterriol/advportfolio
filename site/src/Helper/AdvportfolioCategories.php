<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\Categories;

/**
 * Category Tree
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolio
 */
class AdvportfolioCategories extends Categories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__advportfolio_projects';
		$options['extension'] = 'com_advportfolio';

		parent::__construct($options);
	}
}