<?php
/**
 * @version     $Id$
 * @package     JSN_PowerAdmin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JSNFactory::import('components.com_modules.controllers.modules');
JSNFactory::localimport('libraries.joomlashine.modules');
error_reporting(0);
class PoweradminControllerModules extends ModulesControllerModules
{
	/**
	 *
	 * Rawmode load data json
	 */
	public function loadModulesJsonData()
	{
		JSNFactory::localimport('libraries.joomlashine.mode.rawmode');
		$position = JRequest::getVar('position', '');
		$Itemid   = JRequest::getVar('currItemid', 0);

		$jsnrawmode = JSNRawmode::getInstance();
		$jsnrawmode->setParam('Itemid', $Itemid);
		$jsnrawmode->renderPosition($position);
        echo $jsnrawmode->getScript('position', 'JSON', $position);
	    jexit();
	}
}