<?php
/**
 * @version    $Id: subinstall.php 17306 2012-10-23 09:31:25Z cuongnm $
 * @package    JSNSample
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if (is_file($base = dirname(__FILE__) . '/admin/jsninstaller.php'))
{
	require_once $base;
}
elseif (is_file($base = dirname(__FILE__) . '/jsninstaller.php'))
{
	require_once $base;
}

/**
 * Class for finalizing JSN Sample installation.
 *
 * @package  JSNSample
 * @since    1.0.0
 */
class Com_ImageShowInstallerScript extends JSNInstallerScript
{
}
