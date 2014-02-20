<?php
/**
 * @version    $Id: items.php 17922 2012-11-02 10:10:38Z cuongnm $
 * @package    JSN_Sample
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class for retrieving items for button menu generation.
 *
 * @package  JSN_Sample
 * @since    1.0.0
 */
class JSNSampleItems
{
	var $_db = null;

	/**
	 * Defined in the sample items
	 *
	 *  return void
	 */
	function JSNSampleItems()
	{
		if ($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_sample/tables');
	}

	/**
	 * Render list menu items
	 *
	 * @param   type  $limit  Limit number
	 *
	 * @return html item lists
	 */
	public function getItems($limit = 1)
	{
		$query = 'SELECT  item_title, item_id  FROM #__jsn_sample_items ORDER BY ordering ASC';
		$this->_db->setQuery($query, 0, $limit);

		return $this->_db->loadObjectList();
	}
}
