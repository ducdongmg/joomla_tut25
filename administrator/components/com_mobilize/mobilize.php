<?php

/**
 * @version     $Id$
 * @package     Mobilize
 * @subpackage  Mobilize
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
// Get application object
$app = JFactory::getApplication();

// Get input object
$input = $app->input;

// Access check
if (!JFactory::getUser()->authorise('core.manage', $input->getCmd('option', 'com_mobilize')))
{
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Initialize common assets
require_once JPATH_COMPONENT_ADMINISTRATOR . '/bootstrap.php';
// Require helper file
JLoader::register('JSNMobilizeHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mobilize.php');
// Check if all dependency is installed
require_once JPATH_COMPONENT_ADMINISTRATOR . '/dependency.php';

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
// import joomla controller library
jimport('joomla.application.component.controller');

if (strpos('installer + update + upgrade', $input->getCmd('view')) !== false OR JSNVersion::isJoomlaCompatible('2.5'))
{
		// Get the appropriate controller
		$controller = JSNBaseController::getInstance('JSNMobilize');
		$controller = new $controller;

		// Perform the request task
		$controller->execute(JRequest::getCmd('task'));

		// Redirect if set by the controller
		$controller->redirect();
}
