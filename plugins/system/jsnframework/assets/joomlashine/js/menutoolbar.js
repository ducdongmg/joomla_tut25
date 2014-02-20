define([
    'jquery'
    ],

    function ($)
    {
	// Declare JSNMedia contructor
	var JSNMenuToolBar = function(params)
	{
	    this.params = $.extend({}, params);
	    this.lang = this.params.language || {};
	    // Set event handler
	    $(document).ready($.proxy(function() {
		this.initialize();
	    }, this));
	};
	JSNMenuToolBar.prototype = {
	    initialize: function() {
		var self = this;
		$('li.menu-name').bind('mouseleave', function(e)
		{
		    self.hideSubMenu($(this).find(".jsn-submenu"));
		});	
		$('.jsn-submenu').bind('mouseleave', function(e)
		{
		    self.hideSubMenu($(this));
		});
	    },
	    hideSubMenu: function(_this)
	    {
		$(_this).css({
		    "left":"auto",
		    "right":"0"
		});
		setTimeout(function(){
		    $(_this).css({
			"left":"",
			"right":""
		    });
		}, 500);
	    }
	}

	return JSNMenuToolBar;
    });