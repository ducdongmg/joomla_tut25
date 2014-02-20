<?php
/**
 * @version     $Id: installer.php 17272 2012-10-21 12:12:54Z cuongnm $
 * @package     JSN_Sample
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import JSN Installer library
require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/joomlashine/installer/controller.php';
error_reporting(0);
/**
 * Installer controller
 *
 * @package     JSN_Sample
 * @since       1.1.0
 */
class PoweradminControllerInstaller extends JSNInstallerController
{
	public function installPaExtension()
	{
		$this->model = $this->getModel('installer');
		JSNFactory::import('components.com_installer.helpers.installer');
		$canDo	= InstallerHelper::getActions();
		if ($canDo->get('core.manage'))
		{
			try{
				$rs	= $this->model->download();
				$this->input->set('package', $rs);

				// Set extension parameters
				$_GET['package'] 	= $rs;
				$_GET['type']		= 'plugin';
				$_GET['folder']		= 'jsnpoweradmin';
				$_GET['publish']	= 1;
				$_GET['client']		= 'site';
				$_GET['name']		= str_ireplace(JSN_POWERADMIN_EXT_IDENTIFIED_NAME_PREFIX, '', $_GET['identified_name']);

				if ($this->model->install($rs))
				{
					require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extensions.php';
					// Enable extension suport
					$identifiedName	= str_ireplace(JSN_POWERADMIN_EXT_IDENTIFIED_NAME_PREFIX, '', $_GET['identified_name']);
					try
					{
						JSNPaExtensionsHelper::enableExt($identifiedName);
					}catch (Exception $ex)
					{
						exit ('notenabled');
					}
				}
			}
			catch (Exception $ex)
			{
				exit ($ex->getMessage());
			}
			exit('success');
		}

	}
}
