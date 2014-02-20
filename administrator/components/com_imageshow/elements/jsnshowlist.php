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



class JFormFieldJsnshowlist extends JFormField
{
	protected function getInput()
	{
		$definePath = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_imageshow' . DS . 'imageshow.defines.php';
		
		if (is_file($definePath))
		{
			include_once $definePath;
		}
		
		$enabledCSS 	= ' jsn-disable';
		$menuid			= JRequest::getInt('id');
		$app 			= JFactory::getApplication();
		$showlistID 	= $app->getUserState('com_imageshow.add.showlist_id');
		if ($showlistID != 0)
		{
			$this->value = $showlistID;
			$app->setUserState('com_imageshow.add.showlist_id', 0);
		}

		$db = JFactory::getDBO();
		
		$query			= 'SELECT COUNT(*) FROM #__imageshow_showlist';
		$db->setQuery($query);
		$totalShowlist = $db->loadResult();
		
			
		$document = JFactory::getDocument();
		$document->addStyleSheet(JSN_URL_ASSETS . '/3rd-party/jquery-ui/css/ui-bootstrap/jquery-ui-1.8.16.custom.css');
		$document->addStyleSheet(JSN_URL_ASSETS . '/joomlashine/css/jsn-gui.css');

		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::script('jsnis_module.js','modules/mod_imageshow/assets/js/');
		//JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery/jquery-1.7.1.min.js');
		JSNHtmlAsset::jquery();
		JSNHtmlAsset::addScript(JSN_URL_ASSETS . '/3rd-party/jquery-ui/js/jquery-ui-1.9.0.custom.min.js');
		JHTML::script('conflict.js','administrator/components/com_imageshow/assets/js/joomlashine/');
		JHTML::script('window.js','administrator/components/com_imageshow/assets/js/joomlashine/');

		$jsCodeAddShowlistButton = '';
		if ($totalShowlist >= 3 && strtolower(JSN_IMAGESHOW_EDITION) == 'free')
		{
			$jsCodeAddShowlistButton = "
				(function($){
					$(document).ready(function () {
	
						$('.jsn-is-add-showlist-modal').click(function(event){
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
		
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($jsCode . $jsCodeAddShowlistButton);
		//build the list of categories
		$query = 'SELECT a.showlist_title AS text, a.showlist_id AS id'
		. ' FROM #__imageshow_showlist AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('JSN_FIELD_SELECT_SHOWLIST').' -', 'id', 'text' );
		$results 	= array_merge( $results, $data);

		if ($data)
		{
			$enabledCSS = '';
			if ((!$menuid && is_null($showlistID)))
			{
				$this->value = $data[0]->id;
			}
		}
		else
		{
			$this->value = '0';
		}
		$html  = "<div id='jsn-showlist-icon-warning'>";
		$html .= JHTML::_('select.genericList', $results, $this->name, 'class="inputbox jsn-select-value'.$enabledCSS.'"', 'id', 'text', $this->value, $this->id);
		if (!$data)
		{
			$html 	.= '<span><i>'.JText::_('JSN_DO_NOT_HAVE_ANY_SHOWLIST').'</i></span>';
		}
		$html .= "<span><i class=\"jsn-icon16 jsn-icon-warning-sign icon-warning".$enabledCSS."\" id = \"showlist-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('JSN_FIELD_TITLE_SHOWLIST_WARNING')."</p>".JText::_('JSN_FIELD_DES_SHOWLIST_WARNING')."</span></i></span>";
		$html .= "<a class=\"jsn-link-edit-showlist jsn-is-showlist-modal\" id=\"jsn-link-edit-showlist\" href=\"javascript: void(0);\" rel='{\"action\": \"edit\"}' title=\"".JText::_('EDIT_SELECTED_SHOWLIST')."\"><i class=\"jsn-icon16 jsn-icon-pencil\" id = \"showlist-icon-edit\"></i></a>";
		if ($totalShowlist >= 3 && strtolower(JSN_IMAGESHOW_EDITION) == 'free')
		{
			$html .= "<a class=\"jsn-is-add-showlist-modal\" href=\"index.php?option=com_imageshow&controller=showlist&task=add\" rel='{\"action\": \"add\"}' title=\"".JText::_('CREATE_NEW_SHOWLIST')."\"><i class=\"jsn-icon16 jsn-icon-plus\" id = \"showlist-icon-add\"></i></a>";
		}
		else
		{
			$html .= "<a class=\"jsn-is-showlist-modal\" href=\"index.php?option=com_imageshow&controller=showlist&task=add\" rel='{\"action\": \"add\"}' title=\"".JText::_('CREATE_NEW_SHOWLIST')."\"><i class=\"jsn-icon16 jsn-icon-plus\" id = \"showlist-icon-add\"></i></a>";
		}
		$html .= "</div>";

		return $html;
	}

	public function showlistDropDownList($name, $id)
	{
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('plgeditor.js','administrator/components/com_imageshow/assets/js/joomlashine/');
		$value = 0;
		$db = JFactory::getDBO();
		$query = 'SELECT a.showlist_title AS text, a.showlist_id AS id'
		. ' FROM #__imageshow_showlist AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('PLG_EDITOR_FIELD_SELECT_SHOWLIST').' -', 'id', 'text');
		$results 	= array_merge( $results, $data);
		$html  = "<div id='jsn-showlist-icon-warning'>";
		if (!$data)
		{
			$html 	.= '<i>'.JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWLIST').'</i>';
		}
		else
		{
			$value = $data[0]->id;
			$html .= JHTML::_('select.genericList', $results, $name, 'class="span4 jsn-select-value" id="'.$id.'"', 'id', 'text', $value);
			$html .= "<span class=\"jsn-icon16 jsn-icon-warning-sign icon-warning\" id = \"showlist-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('PLG_EDITOR_TITLE_SHOWLIST_WARNING')."</p>".JText::_('PLG_EDITOR_DES_SHOWLIST_WARNING')."</span></span>";
			$html .= "<a class=\"jsn-link-edit-showlist\" id=\"jsn-link-edit-showlist\" href=\"javascript: void(0);\" target=\"_blank\" title=\"".JText::_('PLG_EDITOR_EDIT_SELECTED_SHOWLIST')."\"><i class=\"jsn-icon16 jsn-icon-pencil\" id = \"showlist-icon-edit\"></i></a>";
		}
		$html .= "<a href=\"index.php?option=com_imageshow&controller=showlist&task=add\" target=\"_blank\" title=\"".JText::_('PLG_EDITOR_CREATE_NEW_SHOWLIST')."\"><i class=\"jsn-icon16 jsn-icon-plus\" id = \"showlist-icon-add\"></i></a>";
		$html .= "</div>";

		return $html;
	}

	public function showlistDropDownListFrontEnd($name, $id)
	{
		//JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('plgeditor.js','components/com_imageshow/assets/js/');
		$value = 0;
		$db = JFactory::getDBO();
		$query = 'SELECT a.showlist_title AS text, a.showlist_id AS id'
		. ' FROM #__imageshow_showlist AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('PLG_EDITOR_FIELD_SELECT_SHOWLIST').' -', 'id', 'text');
		$results 	= array_merge( $results, $data);
		$html  = "<div id='jsn-showlist-icon-warning'>";
		if (!$data)
		{
			$html 	.= '<i>'.JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWLIST').'</i>';
		}
		else
		{
			$value = $data[0]->id;
			$html .= JHTML::_('select.genericList', $results, $name, 'class="span4 jsn-select-value" id="'.$id.'"', 'id', 'text', $value);

		}
		$html .= "</div>";

		return $html;
	}
}
?>