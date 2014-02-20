<?php

/**
 * @version     $Id: view.html.php 19013 2012-11-28 04:48:47Z thailv $
 * @package     JSNUniform
 * @subpackage  About
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * View class for a list of Forms.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_uniform
 * @since       1.5
 */
class JSNMobilizeViewAbout extends JSNBaseView
{

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 */
	function display($tpl = null)
	{
		// Get config parameters
		$config = JSNConfigHelper::get();

		// Initialize toolbar
		JSNMobilizeHelper::initToolbar('JSN_MOBILIZE_PAGE_ABOUT_TITLE', 'mobilize-about', false);
		// Get messages
		$msgs = '';

		if (!$config->get('disable_all_messages'))
		{
			$msgs = JSNUtilsMessage::getList('ABOUT');
			$msgs = count($msgs)?JSNUtilsMessage::showMessages($msgs):'';
		}

		// Load the submenu.
		$input = JFactory::getApplication()->input;
		JSNMobilizeHelper::addSubmenu($input->get('view', 'about'));
		// Assign variables for rendering
		$this->assignRef('msgs', $msgs);

		// Display the template
		parent::display($tpl);
		// Load assets
		JSNMobilizeHelper::loadAssets();
	}
}
