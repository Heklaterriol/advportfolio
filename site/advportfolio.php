<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Site
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Include dependencies
require_once __DIR__ . '/helpers/advportfolio.php';
require_once __DIR__ . '/helpers/route.php';

// Get and execute the controller
$controller = BaseController::getInstance('AdvPortfolio', ['default_view' => 'projects']);
$controller->execute(Factory::getApplication()->getInput()->getCmd('task'));
$controller->redirect();