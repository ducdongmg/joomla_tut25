/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISMenuGalleriesCollection = 
{
		init: function()
		{
			JSNISMenuGalleriesCollection.showlistElement();
		},
		
		showlistElement: function()
		{			
			var multipleShowlist 			= $('tmp_multiple_showlist');
			
			if (multipleShowlist == null) return false;
			
			var hiddenMultipleShowlist	 	= $('jform_params_showlist_id');
			var countShowlist				= multipleShowlist.options.length;
	
			if (hiddenMultipleShowlist.value != '')
			{
				var selectedShowlistItems 			= hiddenMultipleShowlist.value.split(',');
				
				var hiddenMultipleShowlistLength 	= selectedShowlistItems.length;
			
				if (countShowlist && hiddenMultipleShowlistLength)
				{
					for (var i = 0; i < countShowlist; i++)
					{
						for (var j = 0; j < hiddenMultipleShowlistLength; j++)
						{
							if (multipleShowlist[i].value == selectedShowlistItems[j] && multipleShowlist[i].value != 0)
							{
								multipleShowlist[i].selected = true;
							}
						}
					}
				}					
			}
			
			multipleShowlist.addEvent('change',function()
			{
				var items = new Array();
				if (countShowlist)
				{
					var index = 0;
					for(var i = 0; i < countShowlist; i++) 
					{				
						if (multipleShowlist[i].selected == true && multipleShowlist[i].value != 0)
						{
							items [index] = multipleShowlist[i].value;
							index++;
						}
					}	
					hiddenMultipleShowlist.value = items.join(',');
				}
			});	
		}
};

window.addEvent('domready', function(){
	JSNISMenuGalleriesCollection.init();
});