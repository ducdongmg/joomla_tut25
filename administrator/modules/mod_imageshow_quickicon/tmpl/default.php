<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$lang 			= JFactory::getLanguage();
$document 		= JFactory::getDocument();
$lang->load('mod_imageshow_quickicon');
$document->addStyleSheet(JURI::root(true).'/administrator/modules/mod_imageshow_quickicon/assets/css/mod_imageshow_quickicon.css');
$buttons 		= modImageShowQuickIconHelper::getButtons();
foreach ($buttons as $button)
{
	echo modImageShowQuickIconHelper::button($button);
}
?>