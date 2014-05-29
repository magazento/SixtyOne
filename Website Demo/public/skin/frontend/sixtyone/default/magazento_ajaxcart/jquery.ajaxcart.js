/* AJAX CART*/

/* Product related variables */
if (optionsPrice == undefined){
    var optionsPrice;
}
if (productAddToCartForm == undefined){
    var productAddToCartForm;
}
if (optionsPrice == undefined){
    var optionsPrice;
}
if (spConfig == undefined){
    var spConfig;
}
if (DateOption == undefined){
    var DateOption;
}    


function setLocation(url){
    
//    var inCart = false;
//    
//    if (window.location.toString().search('/checkout/cart/') != -1){
//        inCart = true;
//    }
    if (url.search('checkout/cart/add') != -1) {
        jQuery().addToCart(url, 'url', 1);
    } else {
        document.location.href = url;
    }
}

jQuery(function($){
    
    /* Add to Cart*/ 
    $.fn.addToCart = function (url, type, qty_to_insert) {   

        $().overlayHide();
        $().loaderShow();

        var pattern = /\/product\/([0-9]+)/
        if (url.search(pattern) != -1) {
            var matches = url.match(pattern);
            if (matches[1] != undefined){
                var url_product_check =  ajaxcart_url.replace('product_id', matches[1]);

                $.ajax({
                  type: "POST",
                  url: url_product_check,
                  success: function(data) {
                        if (data != 0){
                            document.location.href = url;
                        } else {
                            $().sendCartUrl(url, qty_to_insert);
                        }
                  },
                    error: function (x, status, thrownError) {
                      alert("An error occurred: " + status + " " + thrownError);
                    }
                });

            }
        } else {
            $().sendCartUrl(url, qty_to_insert);
        }
    }
    
    /* Add to Cart part 2*/ 
    $.fn.sendCartUrl = function (url, type, qty_to_insert) {      
        url = url.replace('checkout/cart', 'ajaxcart/index/cart/cart');
        
        $.ajax({
          type: "POST",
          url: url,
          data: 'qty='+qty_to_insert,
          success: function(data) {
              
                $('#ajaxcart-hidden-box').html(data);
                var return_message = $('#ajaxcart-hidden-box .ajaxcart-message').html();
                var middle_text = '<div class="ajaxcart-cart-bts">' + $('#ajaxcart-hidden-box .back-ajax-add').html()+'</div>';
                
                $('#ajaxcart-result').html( '<div id="ajaxcart-result_wrapper">'+ return_message + middle_text + '</div>');
                
                var cart_content = $('#ajaxcart-hidden-box .cart_side_ajax').html();
                var cart_total = $('#ajaxcart-hidden-box .cart_total_ajax').html();
                $('.cart .dropdown-content').html(cart_content);
                $('.cart .cart-counter').html(cart_total);
                
                jQuery().replaceDeleteUrls();
                jQuery().resultShow();
                $('#ajaxcart-result').center();                       
                
          },
          error: function (x, status, thrownError) {
            alert("An error occurred: " + thrownError);
          }
        });
                    
    }    

    /* Remove from Cart*/
    $.fn.cartDelete = function (url) { 
        //function cartdelete(url){
        //    showLoading();
        $().loaderShow();

        url = url.replace('checkout/cart', 'ajaxcart/index/cartdelete/cart');
        console.log(url);    
        $.ajax({
          type: "POST",
          url: url,
          success: function(data) {
//              alert(data);
                $('#ajaxcart-hidden-box').html(data);
                var cart_content = $('#ajaxcart-hidden-box .cart_side_ajax').html();
                var cart_total = $('#ajaxcart-hidden-box .cart_total_ajax').html();
                $('.cart .dropdown-content').html(cart_content);
                $('.cart .cart-counter').html(cart_total);
                $().replaceDeleteUrls();
                $().overlayHide();                
          },
          error: function (x, status, thrownError) {
            alert("An error occurred: " + thrownError);
          }
        });
    };    
    
    
    /* Confirmation Box */
    $.fn.resultShow = function () {    
        $().overlayShow();
        $('#ajaxcart-loading').hide();
        $('#ajaxcart-result').show();
        $('#ajaxcart-result')
            .css("position","absolute");

        $('#ajaxcart-result').center();     
    }
    
    /* Loader Box */
    $.fn.loaderShow = function () {    
        $().overlayShow();
        $('#ajaxcart-loading').show();
        $('#ajaxcart-loading')
            .css("position","absolute");
            
        $('#ajaxcart-loading').html($('#ajaxcart-loading-data').html());
        $('#ajaxcart-loading').center();       
    }
    
    
    /* Delete URL */
    $.fn.replaceDeleteUrls = function () {
        if (window.location.toString().search('/checkout/cart/') == -1){
            $("a").each(function() {
                var href = $(this).attr("href");
//                console.log(href);

                try
                {
                    if(href.search('checkout/cart/delete') != -1 ) {
                        $(this).attr('onclick','').unbind('click');
                        $(this).click(function(e) {
                            e.preventDefault();
                            var href = $(this).attr("href");
                            $().cartDelete(href);
                        });
                    }
                }
                catch(e)
                {

                }

            });     
        }
    }
    
    
    /* Overlay */
    $.fn.overlayShow = function () {
        $('#ajaxcart-overlay').show();
        $('#ajaxcart-overlay').animate({opacity: '0.6'}, { queue: false, duration: 100 });
    }    
    $.fn.overlayHide = function () {
        $('#ajaxcart-overlay').css( "opacity", "0.5" );        
        $('#ajaxcart-overlay').hide();
        $('#ajaxcart-loading').hide();
        $('#ajaxcart-result').hide();
    }   
    $(".ajaxcart-overlay").click(function() {
        if ($("#ajaxcart-loading").is(':visible') == false) {
            $().overlayHide();
        }
    });        
    
    /* Centering */
    $.fn.center = function () {
        this.css("position","absolute");
        var top = Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +  $(window).scrollTop()) + "px";
        var left = Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px";
        this.css("top", top);
        this.css("left", left);
        return this;
    }    
    $(window).resize(function() {
        $('#ajaxcart-result').center();        
        $('#ajaxcart-loading').center();             
    }); 
    $(window).scroll(function(){
        $('#ajaxcart-result').center();        
        $('#ajaxcart-loading').center();        
    });
    
    
    
    $().replaceDeleteUrls();
    
    
})
