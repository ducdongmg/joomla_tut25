<?php
/**
 * @version    $Id$
 * @package    JSN.ImageShow
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 *
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


// Get application object
$app = JFactory::getApplication();

// Get input object
$input = $app->input;

global $mainframe, $objectLog;
$mainframe = JFactory::getApplication('administrator');

include_once JPATH_COMPONENT . DS . 'controller.php';
include_once JPATH_COMPONENT . DS . 'classes' . DS . 'jsn_is_factory.php';
// Initialize common assets
require_once JPATH_COMPONENT_ADMINISTRATOR . '/bootstrap.php';
//include_once JPATH_COMPONENT . DS . 'imageshow.defines.php';
include_once JPATH_COMPONENT . DS . 'helpers' . DS . 'media.php';
JLoader::register('JSNISImageShowHelper', JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'imageshow.php');
if (class_exists('JSNHtmlAsset'))
{
	JSNHtmlAsset::addScriptLibrary('jquery.ui', JSN_URL_ASSETS . '/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min', array('jquery'));
}

//JTable::addIncludePath(JPATH_COMPONENT . DS . 'tables');

$option = JRequest::getCmd('option');
$task 	= JRequest::getVar('task');
$tmpl   = $input->getCmd('tmpl');
if ($option != 'image' && $task != 'editimage')
{
	JHTML::_('behavior.mootools');
}

$objShowcaseTheme 		= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objectLog 		  		= JSNISFactory::getObj('classes.jsn_is_log');

//get component version
$objShowcaseTheme->enableAllTheme();

$controller = JRequest::getWord('controller');
$view		= JRequest::getWord('view');

if ($view && $controller !== 'media')
{
	$controller = $view;
}

$canAccess 	= JSNISImageShowHelper::getAccesses($controller);

if (!JFactory::getUser()->authorise('core.manage', $input->getCmd('option')) || !$canAccess)
{
	// Build error object
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

if (strpos($task = JRequest::getCmd('task'), '.') !== false)
{
	list($controller, $task) = explode('.', $task, 2);
}

if ($controller == "updater")
{
	$controller = "update";
}

if ($controller == "configuration")
{
	$controller = "maintenance";
	JRequest::setVar('view', $controller);
}

if ($controller == "update" || $controller == "installer" || $controller == "upgrade")
{
	JRequest::setVar('view', $controller);
}

// Check if all dependency is installed
if ($tmpl !== 'component')
{
	include_once JPATH_COMPONENT_ADMINISTRATOR . '/dependency.php';
}

if ($controller)
{
	$path = JPATH_COMPONENT_ADMINISTRATOR . DS . 'controllers' . DS . $controller . '.php';

	if (file_exists($path))
	{
		require_once $path;
	}
	else
	{
		$controller = '';
	}
}

$classname	= 'ImageShowController' . $controller;
$controller	= new $classname;
$controller->execute($task);

if (strpos('installer + update + upgrade', $input->getCmd('view')) !== false OR JSNVersion::isJoomlaCompatible('2.5'))
{
	$controller->redirect();
}