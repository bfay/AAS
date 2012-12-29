jQuery(document).ready(function()
{	
	// INITIALIZE DROPDOWN MENU
    jQuery('.dd-menu li:has(ul) > a').addClass('dd-submenu-title').append('<span class="dd-arrow"></span>');	
    jQuery('.dd-menu li').hover(function(){	
			// HOVER IN HANDLER
        jQuery('ul:first', this).css({visibility: "visible",display: "none"}).slideDown(250);									
			var path_set = jQuery(this).parents('.dd-menu li').find('a:first');
			jQuery(path_set).addClass('dd-path');						
			jQuery('.dd-menu li a.dd-path').not(path_set).removeClass('dd-path');
			
		},function(){			
			// HOVER OUT HANDLER
		    jQuery('ul:first', this).css({visibility: "hidden"});		 	
	});
		
	//ZOOM effect FancyBox	
	jQuery("a[rel=example_group]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
		}
	});		
			
	/* ----------------- Ajax for Contact form in footer and contact page ------------------ */
	(function( $ ){
	    $.fn.serializeJSON=function() {
	                        var jsonxxx = {}; //json
	                        $.map($(this).serializeArray(), function(n, i){
	                            jsonxxx[n['name']] = n['value'];
	                        });
	                        return jsonxxx;
	    };
	    $.fn.reset = function () {
	        $(this).each (function() { this.reset(); }); 
	    };
	})( jQuery );
			
	jQuery(".contact-button").click(function(e){
	   
	    var form = jQuery(this).parents("form");
	    var formData = jQuery(form).serializeJSON();
	    
	    //Strip any errors messages before submit
	    
	    jQuery(".send-form").find("span").html("");
	    
	    //Request sent from a contact Widget
	    var isWidget = (jQuery(form).find("input[name='from_widget']").val() == 1);
	    
	    var jsonData = {'action':'mls_ajax_handler',
	                    'subaction' : 'contact',
                        'kids_ajax_nonce':kidsAjax.ajax_nonce,
                        'form_data': formData};
    	   	    
	    jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: kidsAjax.ajaxurl,
            data: jsonData,
            success: function(response){
                if(response.status == 'success'){
                  jQuery(form).reset();  
                }else{  
                        if(isWidget){
                            jQuery(form).find(".contact-modal-box").html(response.data.msg).show('slow');
                        }else{
                            jQuery.each(response.data, function(field_name, msg){
                                jQuery(form).find("input[name='"+field_name+"'], textarea[name='"+field_name+"']").next().html('');//after("<span></span>");
                                jQuery(form).find("input[name='"+field_name+"'], textarea[name='"+field_name+"']").next().html("&nbsp;"+msg);
                            }); 
                        }
                }
                if(isWidget){
                    //contact-modal-box
                    jQuery(form).find(".contact-modal-box").html(response.data.msg).show('slow');
                }else{                    
                    jQuery(".page .contact-modal-box span").html(response.data.msg).parent().show('slow');
                }    
            },
            error: function(response){
                if(isWidget){
                    //contact-modal-box
                    jQuery(form).find(".contact-modal-box").html(response.data.msg).show('slow');
                }else{
                    jQuery(".page .contact-modal-box span").html(response.data.msg).parent().show('slow');
                }
            }            
      });
	  e.preventDefault();
	}); // End submit btn
	
	/* Reset button for contact form */
	jQuery(".button-reset").click(function(e){
	    var form = jQuery(this).parents("form");
	    
	    jQuery(form).reset();
	    jQuery(form).find("span").html("");
	    jQuery(".page .contact-modal-box").hide('fast');
	});
	
	/* Comments staff from HERE */
	jQuery(".comment-submit-button").click(function(e){
	    jQuery("#commentform").submit();
	});
	
	/* Prealoading all images in box called container-middle */
	if(typeof settings !== 'undefined'){
	    jQuery(".image").add('#container-middle').preloader({preloader_image_url:settings.themeurl+"/js/preloader/89.gif"}); 
	}
}); //END Document Ready