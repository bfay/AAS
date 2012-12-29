/* Color Sheme Switcher. This script is used only for showcase purpose */
jQuery(document).ready(function() { 
    if(jQuery.cookie("css")) {
        jQuery("link#kidscolor-sheme-css").attr("href",jQuery.cookie("css"));
    }
    jQuery("#nav li a").click(function() {              
        jQuery("link#kidscolor-sheme-css").attr("href",jQuery(this).attr('rel'));
        jQuery.cookie("css",jQuery(this).attr('rel'), {expires: 365, path: '/'});
        return false;
    });
});