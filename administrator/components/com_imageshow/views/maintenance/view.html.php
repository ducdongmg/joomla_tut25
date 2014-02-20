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
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'/elements/html');

/**
 * Maintenance view of JSN ImageShow component
 *
 * @package  JSN.ImageShow
 * @since    2.5
 *
 */

class ImageShowViewMaintenance extends JSNConfigView
{
	/**
	 * Display method
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return	void
	 */

	public function display($tpl = null)
	{
		$config = JSNConfigHelper::get();
		// Get config parameters
		$config = JSNConfigHelper::get();
		$this->_document = JFactory::getDocument();
		JHTML::_("behavior.mootools");
		// Set the toolbar
		JToolBarHelper::title(JText::_('JSN_IMAGESHOW') . ': ' . JText::_('MAINTENANCE_CONFIGURATION_AND_MAINTENANCE'), 'maintenance');
		// Get messages
		$msgs = '';
		if ( ! $config->get('disable_all_messages'))
		{
			$msgs = JSNUtilsMessage::getList('CONFIGURATION_AND_MAINTENANCE');
			$msgs = count($msgs) ? JSNUtilsMessage::showMessages($msgs) : '';
		}

		// Assign variables for rendering
		$this->assignRef('msgs', $msgs);
		$this->_addAssets();
		$this-> addToolbar();

		// Type of screen
		$type  		= JRequest::getWord('type','backup');
		$themeName 	= JRequest::getWord('theme_name');
		$sourceType = JRequest::getString('source_type');

		switch ($type)
		{
			case 'themeparameters':
				$this->addTemplatePath(JPATH_PLUGINS . DS . 'jsnimageshow' . DS . $themeName . DS . 'views' . DS . 'maintenance' . DS . 'tmpl');
				echo $this->loadTemplate('theme_config');
				break;
			case 'profileparameters':
				$this->addTemplatePath(JPATH_PLUGINS . DS . 'jsnimageshow' . DS . $sourceType . DS . 'views' . DS . 'maintenance' . DS . 'tmpl');
				echo $this->loadTemplate('source_config');
				break;
			case 'editprofile':
				$sourceID 		= JRequest::getInt('external_source_id');
				$countShowlist	= JRequest::getInt('count_showlist');
				$imageSource	= JSNISFactory::getSource($sourceType, 'external');
				$imageSource->_source['sourceTable']->load($sourceID);
				$this->assignRef('sourceInfo', $imageSource->_source['sourceTable']);
				$this->assignRef('countShowlist', $countShowlist);
				$this->addTemplatePath(JPATH_PLUGINS . DS . 'jsnimageshow' . DS . 'source' . $sourceType . DS . 'views' . DS . 'maintenance' . DS . 'tmpl');
				echo $this->loadTemplate('edit_source_profile');
				break;
			default:
				// Display the template
				parent::display($tpl);
				break;
		}
	}

	function canInstallLanguage ($locale, $section)
	{
		if($section == 'site')
		{
			$sourcePath = JPATH_ADMINISTRATOR . '/components/com_imageshow/languages/site/'.$locale.'.com_imageshow.ini';
			$langPath   = JPATH_SITE . '/language/'.$locale;
		}
		else
		{
			$sourcePath = JPATH_ADMINISTRATOR . '/components/com_imageshow/languages/admin/'.$locale.'.com_imageshow.ini';
			$langPath   = JPATH_ADMINISTRATOR . '/language/'.$locale;
		}

		return is_dir($langPath) && is_writable($langPath) && is_file($sourcePath);
	}

	function isInstalledLanguage ($locale, $section)
	{
		$langPath = ($section == 'site') ? JPATH_SITE . '/language/'.$locale : JPATH_ADMINISTRATOR . '/language/'.$locale;
		if (!is_dir($langPath))
		{
			return false;
		}
		$langFiles = glob("{$langPath}/{$locale}.com_imageshow.*");
		return count($langFiles) > 0;
	}

	function isJoomlaSupport ($locale, $area)
	{
		$path = ($area == 'site') ? JPATH_SITE : JPATH_ADMINISTRATOR;
		$path.= '/language/' . $locale;
		return is_dir($path);
	}


	/**
	 * Add nesscessary JS & CSS files
	 *
	 * @return void
	 */

	private function _addAssets()
	{
		$input = JFactory::getApplication()->input;
		$objJSNMedia = JSNISFactory::getObj('classes.jsn_is_media');

		! class_exists('JSNBaseHelper') OR JSNBaseHelper::loadAssets();

		$objJSNMedia->addStyleSheet(JURI::root(true) . '/administrator/components/com_imageshow/assets/css/imageshow.css');
		$objJSNMedia->addStyleSheet(JURI::root(true) . '/administrator/components/com_imageshow/assets/css/view.maintenance.css');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/sampledata.js');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/sampledatamanual.js');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/installimagesources.js');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/installshowcasethemes.js');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/installdefault.js');
		//JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery/jquery-1.7.1.min.js');
		JSNHtmlAsset::jquery();
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js');
		$objJSNMedia->addScript(JURI::root(true) . '/administrator/components/com_imageshow/assets/js/joomlashine/jquery.imageshow.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery-ck/jquery.ck.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/bootstrap/js/bootstrap.min.js');

		JSNHtmlAsset::loadScript('imageshow/joomlashine/maintenance', array(
				'pathRoot' => JURI::root(),
				'group'	   => $input->getCmd('g'),
				'language' => JSNUtilsLanguage::getTranslated(array(
						'JSN_IMAGESHOW_SAVE',
						'JSN_IMAGESHOW_CLOSE',
						'JSN_IMAGESHOW_CONFIRM',
						'MAINTENANCE_SOURCE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE_SOURCE_PROFILE'
				))
		));
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 */

	protected function addToolbar()
	{
		jimport('joomla.html.toolbar');
		$path		= JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers';
		$toolbar 	= JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath($path);
		$toolbar->appendButton('JSNHelpButton', '', '', 'index.php?option=com_imageshow&controller=help&tmpl=component', 960, 480);
		JToolBarHelper::divider();
		$toolbar->appendButton('JSNMenuButton');
		
		// Add toolbar menu
		JSNISImageShowHelper::addToolbarMenu();
		
		// Set the submenu
		JSNISImageShowHelper::addSubmenu('maintenance');
	}
}