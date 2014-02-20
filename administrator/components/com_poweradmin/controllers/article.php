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

JSNFactory::import('components.com_content.controllers.article');
JSNFactory::localimport('libraries.joomlashine.modules');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_poweradmin extend com_content
 * @since		1.7
 */
class PoweradminControllerArticle extends ContentControllerArticle
{
	/**
	 *
	 * Redirect to poweradmin article-edit
	 */
	public function edit()
	{
		$editId = JRequest::getVar('id', 0, 'int');
        $app    = JFactory::getApplication();
        $app->setUserState('com_poweradmin.edit.article.id', $editId);
		$this->setRedirect('index.php?option=com_poweradmin&view=article&layout=edit&tmpl=component&id='.$editId);
		$this->redirect();
	}
}