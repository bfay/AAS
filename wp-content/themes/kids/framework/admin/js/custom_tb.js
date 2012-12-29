/* 
   This is BUG fix related to Jquery UI and thickbox. 
   http://wordpress.org/support/topic/wp-32-thickbox-jquery-ui-tabs-conflict 

   Fix: Overwrite Thickbox Core Function tb_remove
*/

function tb_remove() {
 	jQuery("#TB_imageOff").unbind("click");
	jQuery("#TB_closeWindowButton").unbind("click");

	//jQuery("#TB_window").fadeOut("fast",function(){jQuery('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});
	jQuery("#TB_window").fadeOut("fast",function(){jQuery('#TB_window,#TB_overlay,#TB_HideSelect').unload("#TB_ajaxContent").unbind().remove();});
	jQuery("#TB_load").remove();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		jQuery("body","html").css({height: "auto", width: "auto"});
		jQuery("html").css("overflow","");
	}
	jQuery(document).unbind('.thickbox');
	return false;
}