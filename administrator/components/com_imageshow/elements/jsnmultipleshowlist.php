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
class JFormFieldJSNMultipleShowlist extends JFormField
{
	public $type = 'JSNMultipleShowlist';

	public function __construct($form = null)
	{
		parent::__construct($form);
		$this->_app		= JFactory::getApplication('admin');
		$this->_input 	= $this->_app->input;
	}

	protected function getInput()
	{
		$definePath = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_imageshow' . DS . 'imageshow.defines.php';
		
		if (is_file($definePath))
		{
			include_once $definePath;
		}
		
		$pathOnly = JURI::root(true);
		$pathRoot = JURI::root();

		$enabledCSS = ' jsn-disable';
		$menuid		= $this->_input->getInt('id', 0);
		$app 		= JFactory::getApplication();
		$db  		= JFactory::getDBO();
		$doc 		= JFactory::getDocument();

		$doc->addStyleSheet(JSN_URL_ASSETS . '/3rd-party/jquery-ui/css/ui-bootstrap/jquery-ui-1.8.16.custom.css');
		$doc->addStyleSheet(JSN_URL_ASSETS . '/joomlashine/css/jsn-gui.css');

		$doc->addStyleSheet($pathOnly . '/administrator/components/com_imageshow/assets/css/imageshow.css');
		$doc->addStyleSheet($pathOnly . '/administrator/components/com_imageshow/assets/css/menu.galleries.collection.css');
		$doc->addScript($pathOnly . '/administrator/components/com_imageshow/assets/js/joomlashine/menu.galleries.collection.js');
		$doc->addScript($pathOnly . '/administrator/components/com_imageshow/assets/js/joomlashine/jsn.is.jquery.safe.js');
		JSNHtmlAsset::jquery();
		$doc->addScript($pathOnly . '/administrator/components/com_imageshow/assets/js/joomlashine/jsn.is.conflict.js');
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min.js');
		$doc->addScript($pathOnly . '/administrator/components/com_imageshow/assets/js/joomlashine/window.js');

		$query = 'SELECT COUNT(*) FROM #__imageshow_showlist';
		$db->setQuery($query);
		$totalShowlist = $db->loadResult();
		
		if ($totalShowlist >= 3 && strtolower(JSN_IMAGESHOW_EDITION) == 'free')
		{
			$jsCode = "
				(function($){
					$(document).ready(function () {
	
						$('.jsn-is-showlist-modal').click(function(event){
							event.preventDefault();
							var cfm = $('<div id=\"jsn-is-module-backend-confirmbox-container\" style=\"padding:10px; overflow:hidden;\"/>').appendTo('body').html('" .JText::_('JSN_IMAGESHOW_YOU_HAVE_REACHED_THE_LIMITATION_OF_3_SHOWLISTS_IN_FREE_EDITION', true). "');
							cfm.dialog({
								width      : 500,
								height     : 250,
								modal      : true,
								draggable  : false,
								resizable  : false,
								title		: '" . JText::_('JSN_IMAGESHOW_UPGRADE_TO_PRO_EDITION_FOR_MORE', true). "',
								buttons :
										[
											{
												text: 'Close',
												click: function (){
													cfm.remove();
												}
											}
										]
							});
						});
					});
				})(jQuery);
			  ";
		}
		else
		{
			$jsCode = "
				var baseUrl = '".JURI::root()."';
				var gIframeFunc = undefined;
				(function($){
					$(document).ready(function () {
						var wWidth  = $(window).width()*0.9;
						var wHeight = $(window).height()*0.85;
						$('.jsn-is-showlist-modal').click(function(event){
							event.preventDefault();
							var link = baseUrl+'administrator/'+$(this).attr('href')+'&tmpl=component';
							var save_button_lable = '".JText::_('JSN_IMAGESHOW_SAVE_AND_SELECT', true)."';
							var JSNISShowlistWindow = new $.JSNISUIWindow(link,{
									width: wWidth,
									height: wHeight,
									title: '".JText::_('JSN_IMAGESHOW_SHOWLIST_SETTINGS')."',
									scrollContent: true,
									buttons:
									[{
										text:save_button_lable,
										click: function (){
											if(typeof gIframeFunc != 'undefined')
											{
												gIframeFunc();
											}
											else
											{
												console.log('Iframe function not available')
											}
										}
									},
									{
										text: '".JText::_('JSN_IMAGESHOW_CANCEL', true)."',
										click: function (){
											$(this).dialog('close');
										}
									},
									]
							});
						});
					});
				})(jQuery);
			  ";
		}
		$doc->addScriptDeclaration($jsCode);

		$query	= $db->getQuery(true);
		$query->clear();
		$query->select('a.showlist_title AS text, a.showlist_id AS id');
		$query->from($db->quoteName('#__imageshow_showlist') . ' AS a');
		$query->where('a.published = ' . $db->quote(1));
		$query->order('a.ordering ASC');
		$db->setQuery($query);

		$results = $db->loadObjectList();
		if ($results)
		{
			$enabledCSS = '';
			if (!$menuid)
			{
				$this->value = $results[0]->id;
			}
		}
		$html  		 = "<div id='jsn-showlist-container'>";

		if (!$results)
		{
			$html 		.= '<span class="jsn-menu-alert-message">'.JText::_('JSN_DO_NOT_HAVE_ANY_SHOWLIST').'</span>';
			$this->value = '';
		}
		else
		{
			$html .= JHTML::_('select.genericList', $results, 'tmp_multiple_showlist', 'class="inputbox jsn-select-value'.$enabledCSS.'" size="6" multiple="multiple"', 'id', 'text');
		}
		$html 		.= '<input type="hidden" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'"/>';
		$html 		.= "<a class=\"jsn-is-showlist-modal\" href=\"index.php?option=com_imageshow&controller=showlist&task=add\" rel='{\"action\": \"add\"}' title=\"".JText::_('CREATE_NEW_SHOWLIST')."\"><i class=\"jsn-icon16 jsn-icon-plus\" id = \"showlist-icon-add\"></i></a>";
		$html 		.= "</div>";
		return $html.'';
	}
}
?>