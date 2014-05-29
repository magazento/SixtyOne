jQuery.noConflict();

jQuery.setObservers = function() {

        
    jQuery( ".slider-range" ).slider({
        range: true,
        animate: true,
        step:1,
        min: categoryMinPrice,
        max: categoryMaxPrice,
        values: [ categoryMinPrice, categoryMaxPrice ]
    });
    
    jQuery.each(jQuery(".ajax-option-select"), function() {
        jQuery(this).on("click",function(){
            jQuery.ProcessEvent(this.value);
        })
    });  
    jQuery.each(jQuery(".ajax-option-checkbox"), function() {
        jQuery(this).on("click",function(){
            jQuery.ProcessEvent(this.value);
        })
    });  
    
    jQuery.each(jQuery(".ajax-option-link"), function() {
        jQuery(this).on("click",function(event){
            event.preventDefault();
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });   
    
    jQuery.each(jQuery("a.list"), function() {
        jQuery(this).on("click",function(event){
            event.preventDefault();
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    }); 
    
    jQuery.each(jQuery("a.grid"), function() {
        jQuery(this).on("click",function(event){
            event.preventDefault();
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });   
    
    jQuery.each(jQuery("div.sort-by select"), function(element) {
        jQuery(this).attr("onchange" , "");
        jQuery(this).on("change",function(event){
            var query = jQuery.getUrlQuery(this.value);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });   
    jQuery.each(jQuery("div.sort-by a"), function(element) {
        jQuery(this).on("click",function(event){
            event.preventDefault();     
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });  
    
    jQuery.each(jQuery("div.sort-by a"), function(element) {
        jQuery(this).on("click",function(event){
            event.preventDefault();     
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });   
    
    jQuery.each(jQuery("div.limiter a"), function(element) {
//        jQuery(this).attr("onchange" , "");        
        jQuery(this).on("click",function(event){
            event.preventDefault();     
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });  
    
    jQuery.each(jQuery("div.pages li a"), function(element) {
        jQuery(this).on("click",function(event){
            event.preventDefault();     
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });  
    
    jQuery.each(jQuery("a.btn-remove"), function(element) {
        jQuery(this).on("click",function(event){
            event.preventDefault();     
            var query = jQuery.getUrlQuery(this.href);
            if (query) {
                var url = categoryUrl + query;
            } else {
                var url = categoryUrl;
            }
            jQuery.ProcessEvent(url);
        })
    });  

    jQuery("#from-price-range").on("change",function(event){
        var fromValue = jQuery("#from-price-range").val();
        var toValue = jQuery("#to-price-range").val();
        if ( fromValue < categoryMinPrice ) {
            fromValue = categoryMinPrice;
            jQuery("#from-price-range").val(categoryMinPrice);
        }
        if ( fromValue > toValue ) {
            fromValue = toValue;
            jQuery("#from-price-range").val(fromValue);
        }
        jQuery( ".slider-range" ).slider( "option", "values", [fromValue,toValue] );

    })
    jQuery("#to-price-range").on("change",function(event){
        var fromValue = jQuery("#from-price-range").val();
        var toValue = jQuery("#to-price-range").val();
        if ( toValue > categoryMaxPrice ) {
            toValue = categoryMaxPrice;
            jQuery("#to-price-range").val(categoryMaxPrice);
        }
        if ( toValue < fromValue ) {
            toValue = fromValue;
            jQuery("#to-price-range").val(toValue);
        }
//        alert();
        jQuery( ".slider-range" ).slider( "option", "values", [fromValue,toValue] );
    })
    
}
jQuery.BindSlider = function() {
     jQuery( ".slider-range" ).bind( "slidechange", function(event, ui) {
        jQuery('#from-price-range').val(ui.values[0]); 
        jQuery('#to-price-range').val(ui.values[1]);           
        jQuery.ProcessEvent(jQuery.getPriceQuery(ui.values[0],ui.values[1]));
    });        
}



jQuery.getUrlQuery = function(url) {
    var Href = url;
    if ( Href.indexOf("?") > -1 ){
        var data = Href.substr(Href.indexOf("?")).toLowerCase();
        return data;
    }
    return false;
}

jQuery.getPriceQuery = function(from,to) {
        
        var strReturn = "";
        var price = 'price='+from+','+to;        
        var strHref = document.location.hash;
        
        if ( strHref.indexOf("#") > -1 ){
            var strQueryString = strHref.replace('#', '');
            var aQueryString = strQueryString.split("&");
            for ( var i = 0; i < aQueryString.length; i++ ){
                if (aQueryString[i].indexOf("price=") == -1 ){
                    strReturn= strReturn + aQueryString[i] + "&" ;
                }
            }
        }
        
        strReturn = "?" + strReturn + price;
        return strReturn;
    }



jQuery.ProcessEvent = function(url) {
////    alert('Check ProcessEvent URL param')
////    window.location = url;
//
//    var hash = "";
    
    if ( url.indexOf("catalogsearch/result") > -1 ){
        var hash = url.substring(url.indexOf('?'));
        hash = hash.replace('?', '');        
//        alert(hash);
        document.location.hash = hash;
    } else {
        hash = url.replace('?', '#');
        document.location = hash; 
//        alert(hash);
    }





    jQuery.ShowLoader();
//    alert(url);
    jQuery.getJSON(url, function(jsondata) {
        var filter = jsondata.filter;
        jQuery('#filter-json-temp').html(filter);
        filter =  jQuery('#filter-json-temp .block-layered-nav').html();
        jQuery('.block-layered-nav').html(filter);
        jQuery('.category-products').html(jsondata.list);
//        jQuery('.category-products').html(jsondata.categoryName + " " + jsondata.list);
        jQuery.setObservers();

        jQuery(".slider-range" ).slider({
            values: [ jsondata.minPrice, jsondata.maxPrice ]
        });
        jQuery('#from-price-range').val(jsondata.minPrice); 
        jQuery('#to-price-range').val(jsondata.maxPrice);          
        
        jQuery.BindSlider(); 
        jQuery.HideLoader();
    });

}




// ======================================= LOADERS ====
jQuery.ShowLoader = function() {
    var nav = jQuery(".block-layered-nav")
    nav.append("<div class=\"products-list-loader-layered\"><div></div></div>");
    var nav_position =  nav.position();
    jQuery(".products-list-loader-layered").css({
        'top' : nav_position.top,
        'left' : nav_position.left,
        'width' : nav.width(),
        'height' :  nav.height()
    });


    var maincol = jQuery('.col-main');
    maincol.append("<div class=\"products-list-loader-main\"><div></div></div>");

    var maincol_position =  maincol.position();

    jQuery(".products-list-loader-main").css({
        'top' : maincol_position.top,
        'left' : maincol_position.left,
        'width' : maincol.width(),
        'height' : maincol.height()
    });
}

jQuery.HideLoader = function() {
    jQuery(".col-main .products-list-loader-main").remove();
    jQuery(".block-layered-nav .products-list-loader-layered").remove();
}
