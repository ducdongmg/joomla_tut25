<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 16038 2012-09-14 05:10:06Z hiepnv $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PowerAdminViewUpdate extends JSNUpdateView
{
	public function display ($tpl = null)
	{
		// Get config parameters
		$config = JSNConfigHelper::get();

		// Set the toolbar
		JToolBarHelper::title(JText::_('JSN_POWERADMIN_UPDATE_TITLE'));

		// Add assets
		$document = JFactory::getDocument();

		JSNHtmlAsset::addStyle(PoweradminHelper::makeUrlWithSuffix(JSN_URL_ASSETS.'/3rd-party/bootstrap/css/bootstrap.min.css'));
		JSNHtmlAsset::addStyle(PoweradminHelper::makeUrlWithSuffix(JSN_URL_ASSETS.'/3rd-party/jquery-ui/css/ui-bootstrap/jquery-ui-1.8.16.custom.css'));
		JSNHtmlAsset::addStyle(PoweradminHelper::makeUrlWithSuffix(JSN_URL_ASSETS.'/joomlashine/css/jsn-gui.css'));

		JSNHtmlAsset::addScript(JSN_URL_ASSETS.'/3rd-party/jquery/jquery-1.7.1.min.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS.'/3rd-party/jquery-ui/js/jquery-ui-1.8.16.custom.min.js');

		JSNHtmlAsset::addScript(JSN_URL_ASSETS.'/3rd-party/bootstrap/js/bootstrap.min.js');
		
		$redirAfterFinish = 'index.php?option=com_poweradmin&view=about';
		$this->assign('redirAfterFinish', $redirAfterFinish);
		// Display the template
		parent::display($tpl);
	}

	
}