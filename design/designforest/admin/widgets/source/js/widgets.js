;(function($, window, document, undefined){
	"use strict"

	/**
	 * Sortabble
	 */
	if ( !$().piOrderOfTabs )
	{
		$.fn.piWidgetsSortable = function(options)
		{
			var $self = $(this),
                _oDefault = {};
                _oDefault = $.extend(_oDefault, options);
            $(this).sortable(_oDefault);
		}
	}

})(jQuery, window, document);