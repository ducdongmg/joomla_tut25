<?php

/**
 * @version     $Id: view.html.php 19013 2012-11-28 04:48:47Z thailv $
 * @package     JSNUniform
 * @subpackage  Submission
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');
jimport('joomla.application.helper');

/**
 * View class for a list of Submission.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_uniform
 * @since       1.5
 */
class JSNUniformViewSubmission extends JSNBaseView
{

	protected $_document;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @see     fetch()
	 * @since   11.1
	 */
	public function display($tpl = null)
	{
		// Get input object
		$input = JFactory::getApplication()->input;

		$this->isPrint = $input->getString('print', 'false');
		$this->_form = $this->get('Form');
		$this->_item = $this->get('Item');
		$this->_infoForm = $this->get('InfoForm');
		$dataContentForm = $this->get('FormPages');
		$this->nextAndPreviousForm = $this->get('NextAndPreviousForm');
		$this->_formPages = $dataContentForm;
		$this->_document = JFactory::getDocument();

		$this->_document->addScriptDeclaration("var dataId = {$this->_item->submission_id}");
		$this->_dataSubmission = $this->get('DataSubmission');
		$this->_dataFields = $this->get('DataFields');

		$config = JSNConfigHelper::get();

		// Get messages
		$msgs = '';
		if (!$config->get('disable_all_messages'))
		{
			$msgs = JSNUtilsMessage::getList('SUBMISSION');
			$msgs = count($msgs) ? JSNUtilsMessage::showMessages($msgs) : '';
		}

		// Hide the main menu
		$input->set('hidemainmenu', true);

		// Initialize toolbar
		$this->initToolbar();

		// Assign variables for rendering
		$this->assignRef('msgs', $msgs);

		// Display the template
		parent::display($tpl);

		// Load assets
		JSNUniformHelper::addAssets();
		$this->addAssets();
	}

	/**
	 * Add the libraries css and javascript
	 *
	 * @return void
	 *
	 * @since        1.6
	 */
	protected function addAssets()
	{
		JSNHtmlAsset::registerDepends('uniform/libs/googlemaps/jquery.ui.map', array('jquery', 'jquery.ui'));
		JSNHtmlAsset::registerDepends('uniform/libs/googlemaps/jquery.ui.map.services', array('jquery', 'jquery.ui', 'uniform/libs/googlemaps/jquery.ui.map'));
		JSNHtmlAsset::registerDepends('uniform/libs/googlemaps/jquery.ui.map.extensions', array('jquery', 'jquery.ui', 'uniform/libs/googlemaps/jquery.ui.map'));
		JSNHtmlAsset::addScript('http://maps.google.com/maps/api/js?sensor=false&libraries=places');
		JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/system/jsnframework/assets/3rd-party/jquery/jquery-1.8.2.js');
		JSNHtmlAsset::addScriptLibrary('jquery.ui', JURI::root(true) . '/plugins/system/jsnframework/assets/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min', array('jquery'));
		echo JSNHtmlAsset::loadScript('uniform/submission', array('nextAndPreviousForm' => $this->nextAndPreviousForm), true);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since        1.6
	 */

	protected function initToolbar()
	{
		$bar = JToolBar::getInstance('toolbar');


		$bar->appendButton('Custom', '<a href="#" id="previous-submission" class="toolbar hide"><span class="icon-32-previous" title="' . JText::_('JSN_UNIFORM_PREVIOUS') . '" type="Custom"></span>' . JText::_('JSN_UNIFORM_PREVIOUS') . '</a>');
		$bar->appendButton('Custom', '<a href="#" id="next-submission" class="toolbar hide"><span class="icon-32-next" title="' . JText::_('JSN_UNIFORM_NEXT') . '" type="Custom"></span>' . JText::_('JSN_UNIFORM_NEXT') . '</a>');
		JToolBarHelper::divider();

		JToolBarHelper::apply('submission.apply');
		JToolBarHelper::save('submission.save');

		JToolBarHelper::divider();

		JToolBarHelper::cancel('submission.cancel', 'JTOOLBAR_CLOSE');

		JToolBarHelper::divider();

		JSNUniformHelper::initToolbar('JSN_UNIFORM_SUBMISSIONS_DETAIL', 'uniform-submission');
	}

}
