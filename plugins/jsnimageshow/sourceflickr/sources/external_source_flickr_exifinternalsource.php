<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Image Source Flickr
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_exifinternalsource.php');
class JSNExternalSourceFlickrExifInternalSource extends JSNISExifInternalSource
{
	function JSNExternalSourceFlickrExifInternalSource()
	{
		parent::JSNISExifInternalSource();
	}

	function renderData($exifData)
	{
		$tmpExifData = array();

		if (count($exifData))
		{
			if (isset($exifData['model']) && $exifData['model'] != '' && isset($exifData['make']) && $exifData['make'] != '')
			{
				$tmpExifData [] = @$exifData['make'].'/'.@$exifData['model'];
			}
			if (isset($exifData['exposure']) && $exifData['exposure'] != '')
			{
				$tmpExifData [] = $exifData['exposure'];
			}
			if (isset($exifData['fstop']) && $exifData['fstop'] != '')
			{
				//$tmpExifData [] = 'f/'.$exifData['fstop'];
				$tmpExifData [] = $exifData['fstop'];
			}
			if (isset($exifData['focallength']) && $exifData['focallength'] != '')
			{
				$tmpExifData [] = (float) $exifData['focallength'].'mm';
			}
			if (isset($exifData['iso']) && $exifData['iso'] != '')
			{
				$tmpExifData [] = 'ISO-'.(int) $exifData['iso'];
			}
			if (isset($exifData['flash']) && $exifData['flash'] != '')
			{
				if (is_numeric($exifData['flash']))
				{
					$tmpExifData [] = @$this->flashData[$exifData['flash']];
				}
				else
				{
					$tmpExifData [] = $exifData['flash'];
				}
			}
			else
			{
				$tmpExifData [] = @$this->flashData[16];
			}
			if (count($tmpExifData))
			{
				return implode(', ', $tmpExifData);
			}
			else
			{
				return '';
			}
		}
		return '';
	}
}