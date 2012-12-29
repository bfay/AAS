/* admin_custom_post_type gallery */

jQuery(document).ready(function(){
    
    //Reference to a box where are selected images
    var selected_image_container = jQuery("#selected-images");
    selected_image_container.find("ul").sortable({ tolerance: 'pointer' });
    
    var current_thumb_id = null;
    var thumb_data = null;
    
    //Do some fancy staff
    jQuery("#selected-images").on('hover', 'ul li a', function(event){
        if(event.type == 'mouseenter'){
            jQuery(this).animate({ opacity: 1},{duration:200});
        }else{
            
            jQuery(this).animate({ opacity: 0.6},{duration:200});
        }
    });
    
    /* On click first hide it, them remove the thumb */
    jQuery("#selected-images").on('click', 'ul li a.delete-pic', function(e){
        jQuery(this).parent().fadeOut("fast", function(){jQuery(this).remove();});
        return false;
    });
    
    
    /* Open Modal box with fields. Extra data for image*/
    jQuery("#selected-images").on('click', 'ul li a.edit-pic', function(e){
        
        //Set current thumb id. It will be needed to update thumbs fields.
        current_thumb_id = jQuery(this).data('id');

        //Get Current Thumb data and populate modal box
        
        thumb_data = jQuery(this).parent().find("input[type='hidden']");
        
        /* 
         * 0 - ID 
         * 1 - title
         * 2 - subtitle
         * 3 - text
         */
        
        jQuery("input[name='tmp_title']").val(jQuery(thumb_data[1]).val());
        jQuery("input[name='tmp_subtitle']").val(jQuery(thumb_data[2]).val());
        jQuery("textarea[name='tmp_text']").val(jQuery(thumb_data[3]).val());
        
    });
    
    
    //Close Modal Box
    jQuery("body").on('click', 'input[name="commit"]', function(e){
        //Copy data from tmp fields to thumb data
        if(current_thumb_id){
            jQuery(thumb_data[1]).val(jQuery("input[name='tmp_title']").val());
            jQuery(thumb_data[2]).val(jQuery("input[name='tmp_subtitle']").val());
            jQuery(thumb_data[3]).val(jQuery("textarea[name='tmp_text']").val());
        }
        
        jQuery('#tmp_form').modal('hide');
        
        // Clear data in tmp fields
        jQuery("input[name='tmp_title']").val('');
        jQuery("input[name='tmp_subtitle']").val('');
        jQuery("textarea[name='tmp_text']").val('');
        current_thumb_id = null;
    });
                            
                            
                        
    
    
    /* Click on the Media Gallery thumbs. Clone thumb and append it to selected elements list. */
    jQuery("#media-wrapper").on("click", "ul.thumbs li", function(event){
        var $this = jQuery(this);
        var cloned = $this.clone();
        cloned.find("img").attr("width", 110);
        
        var total_num_of_selected = jQuery("#selected-images li").length;
        total_num_of_selected = total_num_of_selected;// + 1;
        
        var img_id = cloned.find("img").attr("attid");
        jQuery("<a data-id='"+img_id+"' data-toggle='modal' data-target='#tmp_form' href='#tmp_form' class='edit-pic'><img src='"+kids_gallery.theme_admin_url+"/images/pencil-add-icon.png' /></a><a class='delete-pic' href='#'><img src='"+kids_gallery.theme_admin_url+"/images/pencil-delete-icon.png' /></a>").appendTo(cloned);
        jQuery("<input type='hidden' name='gallery_image_id["+total_num_of_selected+"][id]' value='"+img_id+"' />").appendTo(cloned);
        jQuery("<input type='hidden' name='gallery_image_id["+total_num_of_selected+"][title]' />").appendTo(cloned);
        jQuery("<input type='hidden' name='gallery_image_id["+total_num_of_selected+"][subtitle]' />").appendTo(cloned);
        jQuery("<input type='hidden' name='gallery_image_id["+total_num_of_selected+"][text]' />").appendTo(cloned);
        /* Add selected thumb to selected images box */
        cloned.hide().appendTo(selected_image_container.find("ul")).fadeIn();
        event.preventDefault();
    });
    
    
    /* Ajax Call to refresh thumbs. Pagination */
    
    jQuery("#media-wrapper").on('click', "#media-gallery-nav ul li",function(e){
        var jsonData = {'page':jQuery(this).attr("rel"),
                        'action':'get_media_image' //main func
                        }; 
        
        jQuery.ajax({
            type: 'POST',
            dataType: 'html',
            url: ajaxurl ,
            data: jsonData,
            beforeSend : function(){
                jQuery(".loading").css('display', 'block');
                    jQuery("#image-selector ul.thumbs").fadeOut('slow', function(){jQuery(".loading").css('display', 'none');});
                    
            },
            success: function(response){
                jQuery('.loading').css('display', 'none');
                jQuery("#image-selector").html(response);
                jQuery("#image-selector").fadeIn('slow');
            },
            error: function(response){
                jQuery("#image-selector").fadeIn('slow');
            }
        });
        e.preventDefault();
    });
});