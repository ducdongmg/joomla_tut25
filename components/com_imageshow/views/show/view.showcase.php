<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		$showCaseID 	= JRequest::getInt('showcase_id', 0);
		$objUtils		= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL   			= $objUtils->overrideURL();
		$objJSNShowcase	= JSNISFactory::getObj('classes.jsn_is_showcase');
		$row 			= $objJSNShowcase->getShowCaseByID($showCaseID);
		$document 		= JFactory::getDocument();
		
		if(count($row) <=0){
			header("HTTP/1.0 404 Not Found");
			exit();
		}

		$dataObj 	= $objJSNShowcase->getShowcase2JSON($row, $URL);
		$document->setMimeEncoding('application/json');
		echo json_encode($dataObj);
		return;
	}
}
