/* Sliders File */
jQuery(document).ready(function(){
    if(kidsSliders.slider_type == 'cycle_slider'){
      //Pagination related to main slider at home page
        function MLS_cycle_paginate(index, obj){
            return '<li><a href="#">' + (index+1) + '</a></li>'; 
        }
        
        //This is a hack because we have mask over the slider. (PAUSE opts is not working if just sent to cycle)
        if(kidsSliders.opts.pause == 1){
            //BIG SLIDER AT HOME PAGE
            jQuery("#big-slider").hover( function () {
                jQuery('ul#slider-items').cycle('pause');
            }, 
            function () {
              jQuery('ul#slider-items').cycle('resume');
            });
        }
        
        var cycle_confx = {};
        cycle_confx = jQuery.extend({
                                    next:   '.next',
                                    prev:   '.previous',
                                    pager:  '#slider-pagination',
                                    fit:     1,
                                    height:  300,
                                    pagerAnchorBuilder: MLS_cycle_paginate,
                                    before: function(){
                                        jQuery(this).parent().find('li.current').removeClass();
                                    },
                                    after: function(){
                                        jQuery(this).addClass('current');
                                    }
                            }, kidsSliders.opts);
        
        jQuery('ul#slider-items').cycle(cycle_confx);
        
        jQuery(".slider-mask").click(function(){
            $slide_link = jQuery('ul#slider-items li').filter(function(){ 
                                                    return jQuery(this).hasClass("current")
                                                }).find("a");
            if(typeof $slide_link[0] != "undefined"){
                //$slide_link[0].click();
                window.location = $slide_link[0];
            }
        });
        
        //CATEGORY SCROLLER AT HOME PAGE
        jQuery("#scroller").cycle({
            fx:     'blindY', 
            speed:   500, 
            next:   '.down-arrow',
            prev:   '.up-arrow',
            pause:   1,
            timeout: 0
        });
    }else if(kidsSliders.slider_type == 'piece_slider'){
        //console.log("slider.js - piece slider");
    }
}); // jQuery