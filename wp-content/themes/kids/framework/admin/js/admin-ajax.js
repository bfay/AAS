/**
 *  Updating all data from admin panel with ajax/
 */

/* Plugin for arrays */
(function( $ ){
	$.fn.serializeJSON=function() {
    		var jsonxxx = {}; //json
    
    		var multi_fields = []; // array
    		var tmp = '';
    		var u = 0;
    		var tmp_json = '';
    
    		$.map($(this).serializeArray(), function(n, i){
    			//Ako nije prvi i postoji element sa istim imenom
    			if(i>0 && n['name']==tmp){
    				multi_fields.push(n['name']);
    				tmp = '';
    			}else{
    				tmp = n['name'];
    			}
    		});
    		
    		$.map($(this).serializeArray(), function(n, i){
    			var prefix = n['name'].split('__');
    			var field_name = prefix[1];
    				prefix = prefix[0];
    				
    				if(typeof field_name != "undefined") {  // name has not section delimiter => should not be saved. This is used when we need some addition form elements.
    				 
    				   // console.log(field_name);
            			if(typeof jsonxxx[prefix] == 'undefined' || typeof jsonxxx[prefix] == null){
            				jsonxxx[prefix] = {};
            			}
            			tmp_json = jsonxxx[prefix]; // This is an Array MAN!!!
            			if($.inArray(n['name'], multi_fields) > -1){
            				if(u == 0){
            					tmp_json[field_name] = [n['value']]; 
            				}else{
            					tmp_json[field_name].push(n['value']);
            				}
            				u++;
            			}
            			else{
            				tmp_json[field_name] = n['value'];
            				u = 0;
            			}
    				}
    		});
    		return jsonxxx;
	};
})( jQuery );

(function( $ ){
	$.fn.serializeSlidersJSON=function() {
    		var jsonxxx = {}; //json
    		var multi_fields = []; // array holds keys of arrays
    		var tmp = [];
    		var tmp_json = {};
    		
    		//da li ima elemenata sa istim imenom? ako ima onda su to nizovi i tako ih treba prepakovati. + 
    		//ako imaju isti prefix opet u podniz!
    		$.map($(this).serializeArray(), function(n, i){
    			//Ako nije prvi i postoji element sa istim imenom
    		    ii = jQuery.inArray(n['name'], tmp);
    			already_detected = (ii == -1) ? false : true;
    
    			if(i>0 && already_detected){ 
    				multi_fields.push(n['name']);
    				delete tmp[ii];
    			}else{
    				tmp.push(n['name']);
    			}
    		});
    		
    		/* Current slide num */
    		var slide_nox = 0;
    		
    		$.map($(this).serializeArray(), function(n, i){
    
    			    prefix 	   = n['name'].split('__');
    			    section    = prefix[1]; //field_name
    			    
    			    if(prefix.length > 3) //it is a slide field
    			    {
    			    	field_name = prefix[3];
    			    	slide_no   = prefix[2];
    			    }
    			    else{ //no slide
    					field_name = prefix[2];
    					slide_no = 0;
    			    }
    			    
    			    /* Here we are start to built our json */
    
    				if(typeof jsonxxx[section] == 'undefined' || typeof jsonxxx[section] == null){
    					jsonxxx[section] = {};
    				}
    				tmp_json = jsonxxx[section];
    
    				if($.inArray(n['name'], multi_fields) > -1){
    					if(tmp_json[field_name] == undefined) { tmp_json[field_name] = [];}
    						tmp_json[field_name].push(n['value']);
    				}
    				else{
    					if(slide_no > 0){ // only for slides!
    					    
    						if(tmp_json[slide_nox] == undefined && field_name == 'slide_name'){ 
    							
    							$slide_fields = jQuery("*[name^='"+prefix[0]+"__"+prefix[1]+"__"+slide_no+"__slide_"+"']");
    							tmp_json[slide_nox] = {};
    							xxxx = tmp_json[slide_nox];
    							$.each($slide_fields, function(x, y){
    							    /* Fetch filtered name */
    							    name = jQuery(this).attr('name').match(/slide_[a-z]+/);
    							    xxxx[name] = jQuery(this).val();
    							});
    						}else{
    						    //continue; //we already collected all data for this slide.
    						    slide_nox++;
    						}
    					}else{
    						tmp_json[field_name] = n['value'];
    					}
    					u = 0;
    				}
    		});
    		return jsonxxx;
	};
})( jQuery );


// Hide all section and then reveal only the active one
jQuery(document).ready(function() {
		
	jQuery(".save-button-action").click(function(){
		var main_form 	 = jQuery("#main-settings-form");
		var sliders_form = jQuery("#slider_form");
		var jsonData = {};

		var sec_action = '';

		var jsonMainSettingsData = {};
		var jsonSlidersData = {};

		if(jQuery(this).hasClass("save-slider")){
			//Collect all sliders forms
	    	jsonSlidersData = sliders_form.serializeSlidersJSON();
	    	sec_action = 'save_slider';
		}
		else{
			 //saving main settings
			 //Collect - Main Settings Data
			jsonMainSettingsData  = main_form.serializeJSON();
			sec_action = 'save';
		}
		
	    var jsonData = {'main_settings':jsonMainSettingsData,
	    		        'sliders':jsonSlidersData,
	    				'action':'ajaxCallBack',
	    		        'sec_action':sec_action,
	    		        'kids_admin_ajax_nonce':kids_ajax.kids_admin_ajax_nonce}; 
	    
		jQuery.ajax({
				  type: 'POST',
				  dataType: 'json',
				  url: kids_ajax.ajaxurl,
				  data: jsonData,
				  beforeSend : function(){
				            //Clear any previous messages in box
				            jQuery('.modal-body').html('<p>Saving in progress...</p><img src="'+kids_ajax.theme_url+'/framework/admin/images/89.gif">');
				  	 	    jQuery('#myModal').modal({show:true, keyboard: false});
				  },
				  success: function(response){
					   jQuery('#myModal').find(".modal-body").html("<p>"+response.msg+".</p>");
				  },
				  error: function(response){
				  	 jQuery('#myModal').find(".modal-body").html(response.msg);//modal('setContent','pera');
				  }
			});
		//return false;
	}); 
}); //Document Ready