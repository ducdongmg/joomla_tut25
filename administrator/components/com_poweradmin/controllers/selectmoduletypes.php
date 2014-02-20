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

JSNFactory::import('components.com_modules.controllers.module');

class PoweradminControllerSelectmoduletypes extends ModulesControllerModule
{
	public function setModuleType()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the result of the parent method. If an error, just return it.
		$result = parent::add();
		if (JError::isError($result)) {
			return $result;
		}

		// Look for the Extension ID.
		$extensionId = JRequest::getInt('eid');
		if (empty($extensionId)) {
			$this->setRedirect(JRoute::_('index.php?option=com_poweradmin&view=selectmoduletypes&tmpl=component', false));
		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_poweradmin&view=module&layout=edit&tmpl=component&id=0', false));
		}

		$app->setUserState('com_poweradmin.add.module.extension_id', $extensionId);
	}
}
?>