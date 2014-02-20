<?php

/**
 * @version     $Id: default.php 19013 2012-11-28 04:48:47Z thailv $
 * @package     JSNUniform
 * @subpackage  Form
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
$showTitle = false;
$showDes = false;
if (!empty($_GET['show_form_title']) && $_GET['show_form_title'] == 1)
{
	$showTitle = true;
}
if (!empty($_GET['show_form_description']) && $_GET['show_form_description'] == 1)
{
	$showDes = true;
}
if (JSNUniformHelper::checkStateForm($this->_formId))
{
	echo JSNUniformHelper::generateHTMLPages($this->_formId, $this->_formName,'','','',$showTitle,$showDes);
}
