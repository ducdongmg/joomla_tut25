<?php

/**
 * @version     $Id$
 * @package     JSN_Mobilize
 * @subpackage  AdminComponent
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Mobilization view.
 *
 * @package     JSN_Mobilize
 * @subpackage  AdminComponent
 * @since       1.0.0
 */
class JSNMobilizeViewPositions extends JSNPositionsView
{

	public function display($tpl = null)
	{

		$this->setFilterable(true);

		/**
		 * When position clicked
		 * object returned after this event fired is
		 * clicked position
		 * Use $(this)
		 */
		$onPostionClick = "
			if ( !$(this).hasClass('active-position') ){
				window.parent.jQuery.jSelectPosition($(this).find('p').text());				
			}
		";
		$this->addPositionClickCallBack($onPostionClick);

		parent::display($tpl);
	}

}
