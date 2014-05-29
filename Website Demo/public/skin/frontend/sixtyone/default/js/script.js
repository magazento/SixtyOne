jQuery.noConflict();
jQuery(function($){
    
    $('.nav-container').meanmenu({ meanScreenWidth: "767"});
    
    $('.logo-small').hide();
    $('.logo-big').show();
            
    function logoImageSwatch () {
        if ($(window).width() >= 1280 ) {
            $('.logo-big').show();
            $('.logo-small').hide();
        }
        if ( ($(window).width() >= 767 ) && ($(window).width() < 1280 ))  {
            $('.logo-big').hide();
            $('.logo-small').show();
        }
        if ($(window).width() < 767 ) {
            $('.logo-big').hide();
            $('.logo-small').show();
        }
    }

    logoImageSwatch();
    $(window).resize(function() {
        if ($(window).width() >= 767 ) {        
               logoImageSwatch();
        } else {
            $('.website-header').removeClass('fixed').addClass('default').fadeIn(10);
            logoImageSwatch(); 
        }
    }); 
    
    
    var header = $('.website-header'),
    pos = header.offset();
    $(window).scroll(function(){
        if ($(window).width() >= 767 ) {
            if($(this).scrollTop() > pos.top+header.height() && header.hasClass('default')){
                header.fadeOut(10, function(){ 
                    $(this).removeClass('default').addClass('fixed').fadeIn(10);
                    $('.logo-small').show();
                    $('.logo-big').hide();

                });
            } else if($(this).scrollTop() <= 80 && header.hasClass('fixed')){
                header.fadeOut(10, function(){
                    $('.logo-small').hide();
                    logoImageSwatch();
                    $(this).removeClass('fixed').addClass('default').fadeIn(10);
                });
            }
        }
    });
        
    $('.page-title h1').shuffleLetters();
    $('.product-shop .product-name h1').shuffleLetters();
    $('.promo-divider span').shuffleLetters();

    $.fn.pageEffects = function () {
        
        /* Drop-downs */        
        var config = {
            over: function(){
                if ($(this).hasClass('.dropdown-container')){
                    $(this).parent().addClass('over');
                } else {
                    $(this).addClass('over');
                }
                $('.dropdown-content', this).animate({opacity:1, height:'toggle'}, 200);
            },
            timeout: 0, // number = milliseconds delay before onMouseOut
            out: function(){
                var that = this;
                $('.dropdown-content', this).animate({opacity:0, height:'toggle'}, 200, function(){
                    if ($(this).hasClass('.dropdown-container')){
                        $(that).parent().removeClass('over');
                    } else {
                        $(that).removeClass('over');
                    }
                });
            }
        };
        $('.dropdown-container').hoverIntent( config );

        /* Product Grid */
        $(".products-grid .item").hover(
            function() {
                $(this).children('.product-item').children('.promo-sale').hide();
                $(this).children('.product-item').children('.promo-new').hide();
                $(this).children('.product-item-detailed').show();
            },
            function() {
                $(this).children('.product-item').children('.promo-sale').show();
                $(this).children('.product-item').children('.promo-new').show();
                $(this).children('.product-item-detailed').hide();

            }
        );


    };
    
    $().pageEffects();
    // FIX LAYERED NAVIGATIOn
    jQuery(document).ajaxComplete(function() {
        $().pageEffects();
     });         
        


    $(".sidebar-banner").hover(
        function() {
            $(this).animate({opacity: '0.9'}, { queue: false, duration: 500 });
        }, 
        function() {
            $(this).animate({opacity: '1'}, { queue: false, duration: 500 });          
        }
    );
        
    $(".category-description img").hover(
        function() {
            $(this).animate({opacity: '0.8'}, { queue: false, duration: 500 });
        }, 
        function() {
            $(this).animate({opacity: '1'}, { queue: false, duration: 500 });          
        }
    );

    /* Recently Viewed */
    $('#image-gallery').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        startAtSlide: 2,
        keyboardControls: true,
        navNextSelector: $('.gallery-more-views .scroll-left'),
        navPrevSelector: $('.gallery-more-views .scroll-right')
    });

    /* Recently Viewed */
    $('.iosSlider-recently').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        keyboardControls: true,
//        autoSlide: true,
        navNextSelector: $('.block-viewed .scroll-left'),
        navPrevSelector: $('.block-viewed .scroll-right')
    });
    
    /* Recently Viewed */    
    $('.iosSlider-upsell').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        keyboardControls: true,
//        autoSlide: true,
        navNextSelector: $('.box-up-sell .scroll-left'),
        navPrevSelector: $('.box-up-sell .scroll-right')        
    });
    
    /* Related Products */    
    $('.iosSlider-related').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        keyboardControls: true,
//        autoSlide: true,
        navNextSelector: $('.box-related .scroll-left'),
        navPrevSelector: $('.box-related .scroll-right')        
    });
    
    /* Crosssell Products */    
    $('.iosSlider-crosssell').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        keyboardControls: true,
//        autoSlide: true,
        navNextSelector: $('.crosssell .scroll-left'),
        navPrevSelector: $('.crosssell .scroll-right')        
    });
    
    /* New Products */    
    $('.iosSlider-newproducts').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        keyboardControls: true,
        navNextSelector: $('.widget-new-products .scroll-left'),
        navPrevSelector: $('.widget-new-products .scroll-right')        
    });

    /* Sale Products */    
    $('.iosSlider-saleproducts').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        keyboardControls: true,
        navNextSelector: $('.widget-sale-products .scroll-left'),
        navPrevSelector: $('.widget-sale-products .scroll-right')        
    });

    /* Reviews */   
    $('.iosSlider-reviews').iosSlider({
        responsiveSlideWidth: true,
        desktopClickDrag: true,
        responsiveSlides: true,
        autoSlide: true,
        scrollbar: true,
        scrollbarDrag: true,
        scrollbarHide: false,
        scrollbarLocation: 'bottom',
        scrollbarHeight: '6px',
        scrollbarBorder: '1px solid #333',
        scrollbarMargin: '0 30px 16px 30px',
        scrollbarOpacity: '0.75'
        
    });    
    
    /* Prouct Zoom*/
    $("#zoom-image").elevateZoom({gallery:'image-gallery', cursor: 'pointer',  lensShape : "round", zoomType: "lens", lensSize : 200,  galleryActiveClass: "active"});

    $("#zoom-image").bind("click", function(e) {
      var ez =   $('#zoom-image').data('elevateZoom');
      ez.closeAll(); //NEW: This function force hides the lens, tint and window
            $.fancybox(ez.getGalleryList());
      return false;
    });


    /* Product Tabs */
    jQuery('#tabs .tab-item').hide();
    jQuery('#tabs .tab-item:first').show();
    jQuery('#tabs ul li:first').addClass('active');

    jQuery('#tabs ul li a').click(function(){
            jQuery('#tabs ul li').removeClass('active');
            jQuery(this).parent().addClass('active');
            var currentTab = jQuery(this).attr('href');
            jQuery('#tabs .tab-item').hide();
            jQuery(currentTab).show();
            return false;
            });

    /* To Top */
    if ($(window).width() >= 767 ) {    
        $().UItoTop({ easingType: 'easeOutQuart' });
    }

});