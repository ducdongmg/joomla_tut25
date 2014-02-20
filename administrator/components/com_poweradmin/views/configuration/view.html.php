<?php
/**
 * @author    JoomlaShine.com
 * @copyright JoomlaShine.com
 * @link      http://joomlashine.com/
 * @package   JSN Poweradmin
 * @version   $Id: view.html.php 14934 2012-08-10 07:53:33Z thailv $
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Configuration view of JSN Poweradmin component
 */
class PowerAdminViewConfiguration extends JSNConfigView
{

	/**
	 * Display method
	 *
	 * @return	void
	 */
	function display($tpl = null)
	{
		// Get config parameters
		$config = JSNConfigHelper::get('com_poweradmin');
		$this->_document = JFactory::getDocument();

		// Set the toolbar
		JToolBarHelper::title(JText::_('JSN_POWERADMIN_CONFIGURATION_TITLE'), 'maintenance');

		$this->addToolbar();
		JSNHtmlAsset::addScriptLibrary('jquery.ui', '3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min', array('jquery'));

		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		jimport('joomla.html.toolbar');
		$path	= JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers';
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath($path);
	}
}
