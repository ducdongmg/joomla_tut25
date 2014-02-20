<?php
/**
 * @author    JoomlaShine.com
 * @copyright JoomlaShine.com
 * @link      http://joomlashine.com/
 * @package   JSN Framework Sample
 * @version   $Id: view.html.php 15407 2012-08-23 07:27:04Z hiepnv $
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla view library
jimport('joomla.application.component.view');
JSNFactory::localimport('libraries.joomlashine.html.pwgenerate');
/**
 * About view of JSN Framework Sample component
 */
class PoweradminViewAbout extends JView
{

	/**
	 * Display method
	 *
	 * @return	void
	 */
	function display($tpl = null)
	{
		// Get config parameters
		$config = JSNConfigHelper::get();
		$this->_document = JFactory::getDocument();
		JHTML::_("behavior.mootools");
		// Set the toolbar
		JToolBarHelper::title(JText::_('JSN_POWERADMIN_ABOUT_TITLE'), 'about');
		// Get messages
		$msgs = '';
		
		// Assign variables for rendering
		$this->assignRef('msgs', $msgs);
		$this->_addAssets();
		$this-> addToolbar();
		// Display the template
		parent::display($tpl);
	}

	private function _addAssets()
	{
		JSNHtmlAsset::addScriptLibrary('jquery.ui', '3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min', array('jquery'));
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
