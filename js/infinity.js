jQuery.noConflict();
(function($) {
	$('#articles').infinitescroll({
	
		// callback		: function () { console.log('using opts.callback'); },
		navSelector  	: "div#nextPrev",
		nextSelector 	: "div#nextPrev a:first",
		itemSelector 	: "#articles article.post",
		debug		 	: true,
		dataType	 	: 'html',
		// behavior		: 'twitter',
		// appendCallback	: false, // USE FOR PREPENDING
		// pathParse     	: function( pathStr, nextPage ){ return pathStr.replace('2', nextPage ); }
    }, function(newElements){
        
        
    
    	//USE FOR PREPENDING
    	// $(newElements).css('background-color','#ffef00');
    	// $(this).prepend(newElements);
    	//
    	//END OF PREPENDING
    	
    	window.console && console.log('context: ',this);
    	window.console && console.log('returned: ', newElements);
    	
    });
})(jQuery);