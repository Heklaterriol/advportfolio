<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSFactory;
use JoomlaCMSMVCControllerBaseController;

// Include dependencies
require_once __DIR__ . '/helpers/advportfolio.php';
require_once __DIR__ . '/helpers/route.php';

// Get and execute the controller
$controller = BaseController::getInstance('AdvPortfolio', ['default_view' => 'projects']);
$controller->execute(Factory::getApplication()->getInput()->getCmd('task'));
$controller->redirect();