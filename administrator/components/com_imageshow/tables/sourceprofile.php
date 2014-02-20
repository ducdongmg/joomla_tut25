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
class TableSourceProfile extends JTable
{
	var $external_source_profile_id = null;
	var $external_source_id = null;

	function __construct(& $db) {
		parent::__construct('#__imageshow_source_profile', 'external_source_profile_id', $db);
	}
}
?>