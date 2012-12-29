/* Helper functions */
String.prototype.strip_tags = function(){
    tags = this;
    stripped = tags.replace(/<[\/]{0,1}[a-zA-Z]+?>/gi, "");
    return stripped;
}

/**********************************************************************
*
*  all about shortcodes
*
/**********************************************************************/
function mls_iframe(){
    
    url          = jQuery("#mls_sc_iframe_url").val();
    scrolling    = (jQuery("#mls_sc_scrolling").val() == 'on') ? "true" : "false";
    width        = jQuery("#mls_sc_width").val();
    height       = jQuery("#mls_sc_height").val();
    frameborder  = jQuery("#mls_sc_frameborder").val();
    marginheight = jQuery("#mls_sc_marginheight").val();
    
    var data = "";
    
    if(url != '' && (url != undefined)){
        data = '[mls_iframe url="'+url+'" scrolling="'+scrolling+'" width="'+width+'" frameborder="'+frameborder+'" height="'+height+'" marginheight="'+marginheight+'"]';
    }else{
        //Display notify message
        jQuery("#mls_sc_iframe_url").val("http://");
        jQuery("#mls_sc_iframe_url").parent().parent().addClass("error");
    }
    win.send_to_editor(data);
}


function mls_msg(){  
    
    selectedContent = arguments[0].data.selectedContent;
    var data = "";
    var msg_type = jQuery("#mls_sc_msg").val();
    data = "[mls_msg type='"+msg_type+"']"+selectedContent+"[/mls_msg]";
    win.send_to_editor(data);
}

function mls_gallery(){
    
    var data = "";
    var id          = jQuery("#mls_sc_gallery_id").val(); //Gallery ID
    var cols        = jQuery("#mls_sc_cols_num").val(); // Number of Columns
    var g_title     = jQuery("#mls_sc_gtitle_switch").val(); // Main Gallery Title
    var g_subtitle  = jQuery("#mls_sc_gsubtitle_switch").val(); // Main Gallery Subtitle
    var title       = jQuery("#mls_sc_title_switch").val(); //Image Title
    var subtitle    = jQuery("#mls_sc_subtitle_switch").val(); //Image Subtitle
    var desc        = jQuery("#mls_sc_desc_switch").val();

    if(id){
        data = '[mls_gallery id="'+id+'" cols="'+cols+'" g_title="'+g_title+'" g_subtitle="'+g_subtitle+'" title="'+title+'" subtitle="'+subtitle+'" desc="'+desc+'"]';
    }
    win.send_to_editor(data);
}

function mls_yt(){
    var data = "";
    var url          = jQuery("#yt_url").val(); //Gallery ID
    var height       = jQuery("#yt_height").val(); //Gallery ID
    var width        = jQuery("#yt_width").val(); //Gallery ID
    
    data = '[mls_yt yt_url="'+url+'" yt_height="'+height+'" yt_width="'+width+'"]';  
    
    win.send_to_editor(data);    
}

function compareVersion(v1, v2){
    this.v1 = v1;
    this.v2 = v2;

    v1_arr = this.v1.split(".");
    v2_arr = this.v2.split(".");

    var multiplyer = 1000000;
    v1_score = 0
    v2_score = 0;

    for(var i=0; i < v1_arr.length; i++){
        v1_score += v1_arr[i]*multiplyer;
        multiplyer = multiplyer/100;
    }

    multiplyer = 1000000;
    for(var i=0; i < v2_arr.length; i++){
        v2_score += v2_arr[i]*multiplyer;
        multiplyer = multiplyer/100;
    }

    if(v2_score >= v1_score) {
        return true;
    }
    else{
        return false;
    }
}

jQuery(document).ready(function(){
    var jquery_version = jQuery.fn.jquery;
    
    
    /* Radio buttons */
    if(!compareVersion('1.7.0', jquery_version)){
    
        jQuery('body').delegate(".uploader", "click", function() {
                                     viewFormfield = jQuery(this).parent().find("input[type='hidden']");
                                     viewSpanfield = jQuery(this).parent().find(".filename");
                                     tb_show('', 'media-upload.php?type=image&TB_iframe=true');
                                     return false;
                                    });
    
        jQuery("body").delegate(".remove-slide", "click", function(e){
            jQuery(this).parent().parent().fadeOut(function(){
                jQuery(this).remove();
            }); 
        });
    
         jQuery('body').delegate('[data-toggle=buttons-radio] a','click',  function ( e ) { 
              jQuery(this).parent().parent().find("input[type='hidden']").val(jQuery(this).attr("data-state"));
        });
    
    }else{
        jQuery('body').on('click', '[data-toggle=buttons-radio] a', function ( e ) { 
            jQuery(this).parent().parent().find("input[type='hidden']").val(jQuery(this).attr("data-state"));
            e.preventDefault();
        });
    }
});