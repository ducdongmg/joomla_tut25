<?php
/**
 * @version    $Id: dependency.php 17922 2012-11-02 10:10:38Z cuongnm $
 * @package    JSN_POWERADMIN
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Assume that all dependency is installed
$missingDependency = false;

if (strpos('installer + update + upgrade', $input->getCmd('view')) === false)
{
	if ( ! defined('JSN_POWERADMIN_DEPENDENCY'))
	{
		// Load dependency from XML manifest file
		$xml	= simplexml_load_file(dirname(__FILE__) . '/' . substr($input->getCmd('option'), 4) . '.xml');
		$check	= $xml->xpath('subinstall/extension');
	}
	else
	{
		$check = json_decode(JSN_POWERADMIN_DEPENDENCY);
	}

	// Backward compatible
	$checkUpdate = false;

	if (strpos($_SERVER['HTTP_REFERER'], '?option=' . $input->getCmd('option')) !== false
		AND (strpos($_SERVER['HTTP_REFERER'], '&view=update') !== false OR strpos($_SERVER['HTTP_REFERER'], '&view=upgrade') !== false))
	{
		// Checking for dependency is necessary
		$checkUpdate = true;
	}

	// Load installer model for checking dependency
	require_once JPATH_COMPONENT_ADMINISTRATOR . '/models/installer.php';

	$model = new PoweradminModelInstaller;

	if (($result = $model->check($check, $checkUpdate)) !== -1)
	{
		$missingDependency = true;
	}

	if ($missingDependency)
	{
		// Redirect to dependency installer view
		$app->redirect('index.php?option=' . $input->getCmd('option') . '&view=installer');
	}

	// Check compatibility between component and installed Joomla version
	if ( ! JSNVersion::isJoomlaCompatible('2.5'))
	{
		try
		{
			$result = JSNUpdateHelper::check(JSN_POWERADMIN_IDENTIFIED_NAME, '2.5');

			if ($result[JSN_POWERADMIN_IDENTIFIED_NAME])
			{
				$app->redirect('index.php?option=' . $input->getCmd('option') . '&view=update');
			}
			else
			{
				$app->enqueueMessage(JText::_('JSN_POWERADMIN_NOT_COMPATIBLE_MSG'));
			}
		}
		catch (Exception $e)
		{
			$app->enqueueMessage(JText::_('JSN_POWERADMIN_NOT_COMPATIBLE_MSG'));
		}
	}

	// Check compatibility between component and the installed version of JoomlaShine extension framework
	if ( ! JSNVersion::checkCompatibility(JSN_POWERADMIN_IDENTIFIED_NAME, JSN_POWERADMIN_VERSION))
	{
		$app->redirect('index.php?option=' . $input->getCmd('option') . '&view=update');
	}
}
