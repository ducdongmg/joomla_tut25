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
$jsnImageSourceFlickr = array(
	'name' => 'Flickr',
	'identified_name' => 'flickr',
	'type' => 'external',
	'description' => 'Flickr Description',
	'thumb' => 'plugins/jsnimageshow/sourceflickr/assets/images/thumb-flickr.png',
	'sync'	=> true,
	'pagination' => true
);
define('JSN_IS_SOURCEFLICKR', json_encode($jsnImageSourceFlickr));
