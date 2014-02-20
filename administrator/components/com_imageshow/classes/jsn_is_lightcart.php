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
class JSNISLightcart {

	function getErrorCode($alias = 'DEFAULT', $jsSafe = true)
	{
		$alias = strtoupper($alias);
		$errorCodes = array(
			'ERR00' => JText::_('LIGHTCART_MESSAGE_'.$alias.'_ERR00', $jsSafe),
			'ERR01' => JText::_('LIGHTCART_MESSAGE_'.$alias.'_ERR01', $jsSafe),
			'ERR02' => JText::_('LIGHTCART_MESSAGE_'.$alias.'_ERR02', $jsSafe),
			'ERR03' => JText::_('LIGHTCART_MESSAGE_'.$alias.'_ERR03', $jsSafe),
		);
		return $errorCodes;
	}
}