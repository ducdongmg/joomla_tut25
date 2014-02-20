<?php
/**
 * @version    $Id$
 * @package    JSN_Sample
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Menu selection view.
 *
 * @package  JSN_Sample
 * @since    1.0.0
 */
class JSNMobilizeViewMenus extends JSNMenusView
{
	/**
	 * Method for display page.
	 *
	 * @param   boolean  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Exception object.
	 */
	public function display($tpl = null)
	{
		// Add assets
		$baseUrl = JURI::base(true);
		$document = JFactory::getDocument();
		JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/system/jsnframework/assets/3rd-party/jquery/jquery-1.8.2.js');
		JSNHtmlAsset::addScriptLibrary('jquery.ui', JURI::root(true) . '/plugins/system/jsnframework/assets/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min', array('jquery'));
		JSNHtmlAsset::loadScript('mobilize/menus');
		$document->addStyleSheet(JURI::base(true) . '/components/com_mobilize/assets/css/mobilize.css');
		parent::display($tpl);
	}
}
