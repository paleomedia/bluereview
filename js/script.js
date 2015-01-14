jQuery.noConflict();
(function($) {
    var articleWidth = '';
    $(window).ready(function(){
        $('.authors--select-list').customSelect();
        $('.authors--select-list').change(function(){
            var url = $(this).val();
            if (url !== ''){
                window.location = url;
            }
        });
        var artwidth = $('.article--single').width()-20;
        var vidwidth = $('.article--content').innerWidth();
        if (artwidth > vidwidth){
            $('.overflow-left').css("margin-left", vidwidth-artwidth);
        }
    });
    function addHoverStyles(){
        $('.article--list .article--with-image').hover(
            function(){
                $(this).addClass('article--no-image').removeClass('article--with-image');}, 
            function(){
                $(this).removeClass('article--no-image').addClass('article--with-image');});
    }
    
    //when things are loaded
    $(window).load(function(){
//        $('#featured-articles hgroup').addClass('caption');
//        $('#featured-articles').show();
        
        // calculate some things for later
        
        $('#menu-mobile').click(function(){
            $('#full-menu').slideToggle();
        });

        // need to know how high our nav is for sticky menus
        // using the tallest element that will be in the menu
        var navHeight = $('.meta').outerHeight();
        
        // need to know how much space is above our nav for sticky menus
        var aboveHeight = $('.meta').outerHeight()-20;
        
        // set a default column width for articles
        //        var columnSetWidth = 220;
        
        $('.meta--head-social .search-form').hide();
        $('.search-but').click(function(){
            $('.meta--head-social .search-form').slideToggle(200);
            $('#s').focus();
        });
                                    
        // Set masonry
        // and cycle
        
        if($(window).width() > 500){

            // and run with it - init masonry
            setMasonry();
        }
        
        setGallery();
        
        //when resized
        $(window).resize(function() {
            
            // If we are somehow going from mobile size to not-mobile size things break. 
            if($(window).width() > 500){

                setMasonry();
            }
            
            else{
                sizeArticles();
            }
            

        });
        
        //when scroll
        $(window).scroll(function(){
            if($(window).width() > 800){
                
                //if scrolled down more than the header‚Äôs height
                if (($(window).scrollTop() + $(window).height()) >= $('body').height()){
                    
                    // When we are at the bottom of the content, and there is no more to load,
                    // take off the shading that makes it look like there's more
                    $('#footer').addClass('reallyDone');
                }
                else{
                    // If we've scrolled down far enough to have a sticky menu make sense, 
                    // sticky it
                    if ($(window).scrollTop() > aboveHeight){
                        // if yes, sticky our menu
                        $('#sticky-menu').css('top','0').addClass('sticky-nav').parent()
                        .css('padding-top', aboveHeight);

                        // and our footer while we're at it
                        $('#footer').addClass('sticky-nav').css('bottom','0').prev()
                        .css('padding-bottom',navHeight);

                        // And add back in the gradient that means there is more. 
                        // Cause there is if we just stickied our header
                        $('#footer').removeClass('reallyDone')
                    }  
                    else{

                        // if not, well, stick with defaults 
                        // cause that means the top of the page is somewhere on the top 
                        // of the visible screen
                        $('#sticky-menu').removeClass('sticky-nav').parent()
                        .css('padding-top','0');
                        $('#footer').removeClass('reallyDone');
                        $('#footer').removeClass('sticky-nav').prev()
                        .css('padding-bottom', '0');
                    }
                }
//                $('#sidebar>.sidebar>li').each(function(){
//                    if ($(window).scrollTop() > $(this).offset().top){
//                        $(this).children().not('.widgettitle').hide();
//                    }
//                });
                
            }
        });
    });
    
    function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
      && (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
}
    
    
    function centerImgElements(){

        $('.article--with-image .article--meta-container').each(function(){
            var imgHeight = $(this).find('.article--image-wrap img').height();
            var imgWidth = $(this).find('.article--image-wrap img').width();
            var whereV = (($(this).find('.article--image-wrap img').height()-$(this).height())/2);
            var height = $(this).height();
            var whereH = (($(this).find('.article--image-wrap img').width()-$(this).width())/2);
            var width = $(this).width();
            if (imgWidth > width){
                $(this).find('.article--image-wrap img').css('margin-left', -whereH);
            }
            if (imgHeight > height){
                $(this).find('.article--image-wrap img').css('margin-top', -whereV);
            }
    });
        $('.article--with-image .article--featured-image').each(function(){
        whereV = (($(this).children('img').height()-$(this).height())/2);
        whereH = (($(this).children('img').width()-$(this).width())/2);
        $(this).children('img').css('margin-left', -whereH);
        $(this).children('img').css('margin-top', -whereV);
                $(this).children('dd').css('bottom', -whereV);
                $(this).children('dd').css('left', whereH);

    });
    }
    
    // If we are on a page with an image gallery, run it.
    function setGallery(){
        var wrap = $('.article--wrap').innerWidth()-15;
        var content = $('.article .article--content').width();
        var left = -(wrap - content);
        if (wrap > content){
            $('.gallery--wrap').hover(
                function(){
                    $('.gallery--wrap').animate({
                        width: wrap,
                        'margin-left': left
                    });
                    $('#pager-nav').css('margin-left', -left/2);
            }, function(){
                $('.gallery--wrap').animate({
                        width: content,
                        'margin-left': 0
                });
                $('#pager-nav').css('margin-left', 0);
            });
        }
            
        $('.gallery').prepend('<div class="pager" id="pager-prev">&lt;</div><div class="pager" id="pager-next">&gt;</div>');
        $('.gallery').after('<div id="pager-nav"></div>');
        $('.gallery').cycle({
            fx: 'scrollHorz',
            next:   '#pager-next', 
            prev:   '#pager-prev',
            pager:  '#pager-nav',
            pagerTemplate : '<a href="#" >{{slideNum}}</a>',
            slides: '> dl',
            timeout:0,
           autoHeight: 'calc'
        })
    }
    
    function galleryMouseOn(){
        
    }
    
    function galleryMouseOff(){
        
    }
    
    function galleryBefore(curr, next, opts, fwd){
        
    }
    function galleryAfter(curr, next, opts, fwd) {

        var $ht = $(this).height() + 20;

        //set the container's height to that of the current slide
        $(this).parent().animate({
            height: $ht
        });
    //  $(this).parent().animate({width: $wid});
    }
    

    function sizeArticles(){
        
        // Gutter Width
        var gutterWidth = 20;
                                
        $('#articles .article').width('auto').height('auto').css('margin-right', 0);
        $('.article--with-image .article--meta-container').each(function(){
            $(this).height($(this).parent().height());
        });
            
        var containerWidth = $('#articles').innerWidth();
        var columnNumber = calcCols(containerWidth);
        if (columnNumber > 1){
            articleWidth = ((containerWidth - (columnNumber * gutterWidth))/columnNumber);
            var featuredWidth = (articleWidth*2)+gutterWidth;
            $('#articles .article').width(articleWidth);
            $('#articles .article.category-feature').width(featuredWidth);
        }
        else{
            $('#articles .article').width('100%');
            
        }

    }

    // masonry settings
    function setMasonry(){
            
            // Size things as needed
            sizeArticles();
            
            // do masonry on the articles
            $('#articles').masonry({
                
                itemSelector: 'article',
                
                // animation is why we're here right?
                isAnimated: true, 
                
                // and infinity looks better if we animate from the bottom
                isAnimatedFromBottom: true,
                
                gutterWidth: 20,
                
                columnWidth: articleWidth

            });
            centerImgElements();
        if($(window).width() > 800){
            setInfinity();
        }
    }
    
    // Is there enough room for two columns?
    // if not take up all the space, even after resize
    // Yay! math
    function calcCols(containerWidth){
        
        // 320 is one column
        if (containerWidth <= 500){
            columnNumber = 1;
        }
        
        // 440 is two
        if (containerWidth >= 440 && containerWidth < 700){
            columnNumber = 2;
        }
        
        // Between 660 and 960  we can fit three columns
        if (containerWidth >= 700){
            columnNumber = 3;
        }
        return columnNumber;
    }
    
    // Right - and infinite scrolling
    function setInfinity(){
        
        // the articles div is where articles go
        $('.article--list').infinitescroll({
            // and our next and prev stuff needs to be there 
            // for infinite scrolling to act on
            navSelector  : "div#nextPrev",            
            nextSelector : "div#nextPrev a:first",    
            
            // and we are loading more articles
            itemSelector : "#articles article.post",
            
            // not debugging
            debug        : false, 
            
            // Loading bits
            loading: {
                finishedMsg: "No more posts to load.",
                img: "/wp-content/themes/bluereview/images/loading.gif",
                msgText: "Loading more posts...",
                speed: 'slow'
            },
            errorCallback: function () {
                $('#done').addClass('reallyDone');
                $('#done p').text('That\'s all we got.');
            }
        },
        
        // so after we load our infinite elements, we need to do stuff with them
        function( newElements ) {
            
            // like hide them while they are loading
            var newElems =  $( newElements ).css({
                opacity: 0
            });
            
            // and wait to do masonry until the images are done
            newElems.imagesLoaded(function(){
                
                // show elems now they're ready
                newElems.animate({
                    opacity: 1
                });
                
                // Resize new articles 
                sizeArticles();
                
                // then we need to add them to the div
                $('#articles').masonry( 'appended', newElems, true );
                $('#articles').masonry('layout');
                
            });
        });
        centerImgElements();
        addHoverStyles();
    }
    
})(jQuery);