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
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * Showlist view of JSN ImageShow component
 *
 * @package  JSN.ImageShow
 * @since    2.5
 *
 */

class ImageShowViewShowLists extends JView{

	public function display($tpl = null)
	{
		$this->_document = JFactory::getDocument();

		$app 				= JFactory::getApplication();
		$objJSNShowlist 	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNSource 		= JSNISFactory::getObj('classes.jsn_is_source');
		$task 				= JRequest::getString('task');

		$objJSNSource->checkInternalSourceInstalled();

		$list 				= array();
		$model 				= $this->getModel();

		if ($task != 'element' && $task != 'elements')
		{
			$objJSNShowlist->checkShowlistLimition();
		}

		$filterState 		= $app->getUserStateFromRequest('com_imageshow.showlist.filter_state', 'filter_state', '', 'string');
		$filterOrder		= $app->getUserStateFromRequest('com_imageshow.showlist.filter_order', 'filter_order', '', 'cmd');
		$filterOrderDir		= $app->getUserStateFromRequest('com_imageshow.showlist.filter_order_Dir', 'filter_order_Dir', '', 'word');
		$showlistTitle 		= $app->getUserStateFromRequest('com_imageshow.showlist.showlist_stitle', 'showlist_stitle', '', 'string');
		$showlistAccess		= $app->getUserStateFromRequest('com_imageshow.showlist.filter_access', 'filter_access', '', 'string');

		$type = array(0 => array('value'=>'', 'text'=>'- Published -'), 1 => array('value'=>'P', 'text'=>'Yes'), 2 => array('value'=>'U', 'text'=>'No'));
		$lists['type'] 				= JHTML::_('select.genericList', $type, 'filter_state', 'id="filter_state" class="inputbox" onchange="document.adminForm.submit();"'. '', 'value', 'text', $filterState);
		$lists['state']				= JHTML::_('grid.state',  $filterState );
		$lists['access']			= $showlistAccess;
		$lists['showlistTitle'] 	= $showlistTitle;
		$lists['order_Dir'] 		= $filterOrderDir;
		$lists['order'] 			= $filterOrder;

		$this->state 				= $this->get('State');

		if ($task == 'elements')
		{
			$sourceName 	= JRequest::getVar('image_source_name');
			$sourceID 		= JRequest::getInt('external_source_id');
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');

			if ($sourceID)
			{
				$items = $objJSNShowlist->getListShowlistBySource($sourceID, $sourceName);
			}
			else
			{
				$items = array();
			}
		}
		$this->assignRef('items', $items);
		$this->_addAssets();
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 */

	protected function addToolbar()
	{
		jimport('joomla.html.toolbar');
		$canDo 		= JSNISImageShowHelper::getActions();
		$bar     	= JToolBar::getInstance('toolbar');
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist 	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$limitStatus		= $objJSNUtils->checkLimit();
		$count 				= $objJSNShowlist->countShowlist();

		JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWLIST_SHOWLISTS_MANAGER'), 'showlist');

		if ($canDo->get('core.create'))
		{
			if (@$count[0] >= 3 && $limitStatus == true)
			{
				$bar->appendButton('Custom', '<a href="javascript:void(0);" class="toolbar jsn-popup-upgrade"><span class="icon-32-new-disable" title="' . JText::_('JTOOLBAR_NEW') . '" type="Custom"></span>' . JText::_('JTOOLBAR_NEW') . '</a>');
			}
			else
			{
				JToolBarHelper::addNew();
			}
		}

		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::editList();
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList();
			JToolBarHelper::divider();
		}
		// Add the toolbar
		JSNISImageShowHelper::addToolbarMenu();

		$path		= JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers';
		$toolbar 	= JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath($path);
		$toolbar->appendButton('JSNHelpButton', '', '', 'index.php?option=com_imageshow&controller=help&tmpl=component', 960, 480);
		JToolBarHelper::divider();
		$toolbar->appendButton('JSNMenuButton');

		// Add toolbar menu
		JSNISImageShowHelper::addToolbarMenu();

		// Set the submenu
		JSNISImageShowHelper::addSubmenu('showlists');
	}

	/**
	 * Add nesscessary JS & CSS files
	 *
	 * @return void
	 */

	private function _addAssets()
	{
		$objJSNMedia = JSNISFactory::getObj('classes.jsn_is_media');

		! class_exists('JSNBaseHelper') OR JSNBaseHelper::loadAssets();

		$objJSNMedia->addStyleSheet(JURI::root(true) . '/administrator/components/com_imageshow/assets/css/imageshow.css');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js');
		//JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery/jquery-1.7.1.min.js');
		JSNHtmlAsset::jquery();
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/bootstrap/js/bootstrap.min.js');

		JSNHtmlAsset::loadScript('imageshow/joomlashine/showlists', array(
				'pathRoot' => JURI::root(),
				'language' => JSNUtilsLanguage::getTranslated(array(
						'JSN_IMAGESHOW_OK',
						'JSN_IMAGESHOW_CLOSE',
						'CPANEL_UPGRADE_TO_PRO_EDITION_FOR_MORE',
						'UPGRADE_TO_PRO_EDITION',
						'JSN_IMAGESHOW_SHOWLIST_YOU_HAVE_REACHED_THE_LIMITATION_OF_3_SHOWLISTS_IN_FREE_EDITION'
				))
		));
	}
}
