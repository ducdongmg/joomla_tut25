if (typeof jQuery.noConflict() == 'function') 
{	
	var JSNISJquery = jQuery.noConflict();
}

try
{
	if (JSNISJqueryBefore && JSNISJqueryBefore.fn.jquery) 
	{
		jQuery = JSNISJqueryBefore;
	}
} catch (e) {
	console.log(e);
}