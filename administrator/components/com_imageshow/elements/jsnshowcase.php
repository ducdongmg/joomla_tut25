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
class JFormFieldJsnshowcase extends JFormField
{
	protected function getInput()
	{
		$enabledCSS 	= ' jsn-disable';
		$menuid			= JRequest::getInt('id');
		$app 			= JFactory::getApplication();
		$showcaseID 	= $app->getUserState('com_imageshow.add.showcase_id');
		if ($showcaseID != 0)
		{
			 $this->value = $showcaseID;
			 $app->setUserState('com_modules.add.showcase_id', 0);
		}
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
		$jsCode = "
			var baseUrl = '".JURI::root()."';
			var gIframeFunc = undefined;
			(function($){
				$(document).ready(function () {
					var wWidth  = $(window).width()*0.9;
					var wHeight = $(window).height()*0.85;
					$('.jsn-is-showcase-modal').click(function(event){
						event.preventDefault();
						var link = baseUrl+'administrator/'+$(this).attr('href')+'&tmpl=component';
						var save_button_lable = '".JText::_('JSN_IMAGESHOW_SAVE_AND_SELECT', true)."';
						var JSNISShowcaseWindow = new $.JSNISUIWindow(link,{
								width: wWidth,
								height: wHeight,
								title: '".JText::_('JSN_IMAGESHOW_SHOWCASE_SETTINGS')."',
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
		$document->addScriptDeclaration($jsCode);
		$db = JFactory::getDBO();
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('jsnis_module.js','modules/mod_imageshow/assets/js/');
        //build the list of categories
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('JSN_FIELD_SELECT_SHOWCASE').' -', 'id', 'text' );
		$results 	= array_merge( $results, $data);
		if ($data)
		{
			$enabledCSS = '';
			if ((!$menuid && is_null($showcaseID)))
			{
				$this->value = $data[0]->id;
			}
		}
		else
		{
			$this->value = '0';
		}
		$html  = "<div id='jsn-showcase-icon-warning'>";
		$html .= JHTML::_('select.genericList', $results, $this->name, 'class="inputbox jsn-select-value'.$enabledCSS.'"', 'id', 'text', $this->value,  $this->id);
		if (!$data)
		{
			$html 	.= '<span><i>'.JText::_('JSN_DO_NOT_HAVE_ANY_SHOWCASE').'</i></span>';
		}
		$html .= "<span><i class=\"jsn-icon16 jsn-icon-warning-sign icon-warning".$enabledCSS."\" id = \"showcase-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('JSN_FIELD_TITLE_SHOWCASE_WARNING')."</p>".JText::_('JSN_FIELD_DES_SHOWCASE_WARNING')."</span></i></span>";
		$html .= "<a class=\"jsn-link-edit-showcase jsn-is-showcase-modal\" id=\"jsn-link-edit-showcase\" href=\"javascript: void(0);\" rel='{\"action\": \"edit\"}' title=\"".JText::_('EDIT_SELECTED_SHOWCASE')."\"><i class=\"jsn-icon16 jsn-icon-pencil\" id=\"showcase-icon-edit\"></i></a>";
		$html .= "<a class=\"jsn-is-showcase-modal\" href=\"index.php?option=com_imageshow&controller=showcase&task=add\" rel='{\"action\": \"add\"}' title=\"".JText::_('CREATE_NEW_SHOWCASE')."\"><i class=\"jsn-icon16 jsn-icon-plus\" id=\"showcase-icon-add\"></i></a>";
		$html .= "</div>";

		return $html;
	}

	public function showcaseDropDownList($name, $id)
	{
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('plgeditor.js','administrator/components/com_imageshow/assets/js/joomlashine/');
		$value = 0;
		$db = JFactory::getDBO();
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('PLG_EDITOR_FIELD_SELECT_SHOWCASE').' -', 'id', 'text');
		$results 	= array_merge( $results, $data);
		$html  = "<div id='jsn-showcase-icon-warning'>";
		if (!$data)
		{
			$html 	.= '<i>'.JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWCASE').'</i>';
		}
		else
		{
			$value = $data[0]->id;
			$html .= JHTML::_('select.genericList', $results, $name, 'class="span4 jsn-select-value" id="'.$id.'"', 'id', 'text', $value);
			$html .= "<span class=\"jsn-icon16 jsn-icon-warning-sign icon-warning\" id = \"showcase-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('PLG_EDITOR_TITLE_SHOWCASE_WARNING')."</p>".JText::_('PLG_EDITOR_DES_SHOWCASE_WARNING')."</span></span>";
			$html .= "<a class=\"jsn-link-edit-showcase\" id=\"jsn-link-edit-showcase\" href=\"javascript: void(0);\" target=\"_blank\" title=\"".JText::_('PLG_EDITOR_EDIT_SELECTED_SHOWCASE')."\"><i class=\"jsn-icon16 jsn-icon-pencil\" id=\"showcase-icon-edit\"></i></a>";
		}
		$html .= "<a href=\"index.php?option=com_imageshow&controller=showcase&task=add\" target=\"_blank\" title=\"".JText::_('PLG_EDITOR_CREATE_NEW_SHOWCASE')."\"><i class=\"jsn-icon16 jsn-icon-plus\" id=\"showcase-icon-add\"></i></a>";
		$html .= "</div>";

		return $html;
	}

	public function showcaseDropDownListFrontEnd($name, $id)
	{
		//JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('plgeditor.js','components/com_imageshow/assets/js/');
		$value = 0;
		$db = JFactory::getDBO();
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('PLG_EDITOR_FIELD_SELECT_SHOWCASE').' -', 'id', 'text');
		$results 	= array_merge( $results, $data);
		$html  = "<div id='jsn-showcase-icon-warning'>";
		if (!$data)
		{
			$html 	.= '<i>'.JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWCASE').'</i>';
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