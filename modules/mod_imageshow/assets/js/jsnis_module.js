/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISModule = 
{
		init: function()
		{
			JSNISModule.paramSelect();
		},
		
		paramSelect: function()
		{			
			var showlist 	  = $('jform_request_showlist_id') || $('jform_params_showlist_id');
			var countShowlist = showlist.options.length;
			
			if(showlist.value == 0)
			{
				showlist.style.background = '#CC0000';
				showlist.style.color = '#fff';
				$('showlist-icon-warning').setStyle('display', '');	
				$('showlist-icon-edit').setStyle('display', 'none');
				$('jsn-link-edit-showlist').href= "javascript: void(0);";
			}
			else
			{
				showlist.style.background = '#FFFFDD';
				showlist.style.color = '#000';
				$('showlist-icon-warning').setStyle('display', 'none');
				$('showlist-icon-edit').setStyle('display', '');
				$('jsn-link-edit-showlist').href ="index.php?option=com_imageshow&controller=showlist&task=edit&cid[]="+showlist.value;
			}			
			for(var i = 0; i < countShowlist; i++) 
			{				
				showlist.options[i].style.background = '#FFFFDD';
				showlist.options[i].style.color = '#000';
			}	

			var showcase 	  = $('jform_request_showcase_id') || $('jform_params_showcase_id');
			var countShowcase = showcase.options.length;
			if(showcase.value == 0)
			{
				showcase.style.background = '#CC0000';
				showcase.style.color = '#fff';
				$('showcase-icon-warning').setStyle('display', '');
				$('showcase-icon-edit').setStyle('display', 'none');
				$('jsn-link-edit-showcase').href= "javascript: void(0);";				
			}
			else
			{
				showcase.style.background = '#FFFFDD';
				showcase.style.color = '#000';
				$('showcase-icon-warning').setStyle('display', 'none');
				$('showcase-icon-edit').setStyle('display', '');
				$('jsn-link-edit-showcase').href= "index.php?option=com_imageshow&controller=showcase&task=edit&cid[]="+showcase.value;			
			}
			for(var i = 0; i < countShowcase; i++) 
			{				
				showcase.options[i].style.background = '#FFFFDD';
				showcase.options[i].style.color = '#000';
				
			}
		
			showlist.addEvent('change',function()
			{
				if(showlist.value == 0)
				{		
					showlist.style.background = '#CC0000';
					showlist.style.color = '#fff';
					$('jsn-showlist-icon-warning').addClass('show-icon-warning');
					$('showlist-icon-warning').setStyle('display', '');	
					$('showlist-icon-edit').setStyle('display', 'none');
					$('jsn-link-edit-showlist').href= "javascript: void(0);";
				}else{
					showlist.style.background = 'none';
					showlist.style.color = '#000';
					$('showlist-icon-warning').setStyle('display', 'none');	
					$('showlist-icon-edit').setStyle('display', '');
					$('jsn-link-edit-showlist').href= "index.php?option=com_imageshow&controller=showlist&task=edit&cid[]="+showlist.value;
				}
			});	
			
			showcase.addEvent('change',function()
			{
				if(showcase.value == 0){
					showcase.style.background = '#CC0000';
					showcase.style.color = '#fff';
					$('showcase-icon-warning').setStyle('display', '');
					$('showcase-icon-edit').setStyle('display', 'none');
					$('jsn-link-edit-showcase').href= "javascript: void(0);";					
				}else{
					showcase.style.background = 'none';
					showcase.style.color = '#000';
					$('showcase-icon-warning').setStyle('display', 'none');
					$('showcase-icon-edit').setStyle('display', '');
					$('jsn-link-edit-showcase').href= "index.php?option=com_imageshow&controller=showcase&task=edit&cid[]="+showcase.value;					
				}
			});	

			$$('.jsn-icon-warning').each(function(item, i){
				item.addEvent('mouseover', function() {
					$$('.pane-slider')[0].setStyle('overflow', 'visible');
				});
				item.addEvent('mouseout', function() {
					$$('.pane-slider')[0].setStyle('overflow', 'hidden');
				});					
			});
			
		}
};

window.addEvent('domready', function(){
	JSNISModule.init();
});