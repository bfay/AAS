<?php
/* Head function in administration panel*/

if(is_admin()){
    
    //Theme Admin hooks
	add_action('admin_print_scripts-'. $this->admin_panel_hook_name, 'add_admin_scripts');
	add_action('admin_print_styles-' . $this->admin_panel_hook_name, 'add_admin_style');
	
	//Post and page hooks
	add_action('admin_print_scripts-post-new.php', 'add_custom_post_js');
	add_action('admin_print_scripts-post.php'    , 'add_custom_post_js');
	
	add_action('admin_print_scripts-post-new.php', 'add_custom_post_css');
	add_action('admin_print_scripts-post.php'    , 'add_custom_post_css');
	
}

function add_custom_post_css(){
    global $typenow;
    
    if($typenow == 'mlsgallery'){
        wp_enqueue_style('mlsgallery-css', KIDS_ADMIN_URI . '/css/admin_custom_post_type.css');
    }elseif ($typenow == 'post' || $typenow == 'page'){
        wp_enqueue_style('tiny-mce-shortcode-buttons-css', KIDS_ADMIN_URI . '/css/tiny-mce.css');
    }
    
     wp_enqueue_style('bootstrap-css', KIDS_ADMIN_URI . '/bootstrap/css/bootstrap.css');
	 wp_enqueue_style('bootstrap-responsive-css', KIDS_ADMIN_URI . '/bootstrap/css/bootstrap-responsive.css');
	 wp_enqueue_style('admin-style-css', KIDS_ADMIN_CSS_URI . '/admin-style.css');
}
 
function add_custom_post_js(){ 
    global $typenow;
    
    if($typenow == 'mlsgallery'){ 
        wp_enqueue_script('mlsgallery-js', KIDS_ADMIN_URI . '/js/admin_custom_post_type.js', array('jquery','jquery-ui-sortable'));
        wp_localize_script('mlsgallery-js', ThemeSetup::HOOK . '_gallery', array( 'theme_admin_url' => KIDS_ADMIN_URI ));
	    wp_enqueue_script( 'bootstrap', KIDS_ADMIN_URI . '/bootstrap/js/bootstrap.js', array('jquery'));
    }elseif ($typenow == 'post' || $typenow == 'page'){
       //  wp_enqueue_script( 'tiny-mce-shortcode-buttons-css', KIDS_ADMIN_URI . '/js/bootstrap.js', array('jquery'));
    }
}



function add_admin_style() {
    
    $kids_ua = getBrowser();
	wp_enqueue_style('bootstrap-css', KIDS_ADMIN_URI . '/bootstrap/css/bootstrap.css');
	wp_enqueue_style('bootstrap-responsive-css', KIDS_ADMIN_URI . '/bootstrap/css/bootstrap-responsive.css');
	wp_enqueue_style('admin-style-css', KIDS_ADMIN_CSS_URI . '/admin-style.css');
    wp_enqueue_style('admin-style-cssui', KIDS_ADMIN_CSS_URI . '/jquery-ui-1.8.16.custom.css'); 
    
     wp_enqueue_style( 'mls_color-picker-css', KIDS_ADMIN_URI . '/js/colorpicker/css/colorpicker.css');
     wp_enqueue_style( 'mls_color-picker-css', KIDS_ADMIN_URI . '/js/colorpicker/css/layout.css');
	wp_enqueue_style('thickbox'); 
	
   /* if($kids_ua['name'] == 'Internet Explorer' && $kids_ua['version'] < 9.0 ){
        wp_enqueue_style( 'ie-fix', KIDS_ADMIN_CSS_URI . '/ie.css');
    }*/
}

function add_admin_scripts() { 
    
	global $wp_styles;
	
	wp_deregister_script('autosave');

	wp_enqueue_script('jquery');
    $kids_ua = getBrowser();

    $ua['version'] = floatval($kids_ua['version']);
    if($kids_ua['name'] == 'Internet Explorer' && $kids_ua['version'] < 9.0 ){
    	wp_register_script('html5-js', 'http://html5shim.googlecode.com/svn/trunk/html5.js', array("jquery")); 
        wp_enqueue_script( 'html5-js');
    }
    
    wp_enqueue_style('ie8-style', KIDS_ADMIN_URI  . '/css/ie8.css');
	$wp_styles->add_data('ie8-style', 'conditional', 'IE 8');

 	wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');

	wp_enqueue_script('jquery-ui-sortable'); 
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-mouse'); 
    wp_enqueue_script('jquery-ui-widget'); 
    wp_enqueue_script('jquery-ui-slider');

    /* 
       Bug Fix. Jquery UI and Thickbox does not work well together. Check this URL: 
       http://wordpress.org/support/topic/wp-32-thickbox-jquery-ui-tabs-conflict
    */
	wp_register_script( 'custom_tb', KIDS_ADMIN_JS_URI . '/custom_tb.js', array('jquery','media-upload', 'thickbox') );
	wp_enqueue_script( 'custom_tb');
	
    wp_enqueue_script( 'mls_color_picker', KIDS_ADMIN_URI . '/js/colorpicker/js/colorpicker.js', array('jquery'));
    wp_enqueue_script( 'mls_color_picker_eye', KIDS_ADMIN_URI . '/js/colorpicker/js/eye.js', array('jquery'));
    wp_enqueue_script( 'mls_color_picker_layout', KIDS_ADMIN_URI . '/js/colorpicker/js/layout.js', array('jquery'));
		 
	/* all admin Plugins */
	wp_enqueue_script( 'bootstrap', KIDS_ADMIN_URI . '/bootstrap/js/bootstrap.js', array('jquery'));
	//wp_enqueue_script('admin-script-wijmo-open', KIDS_ADMIN_URI . '/bootstrap/js/jquery.wijmo-open.1.5.0.min', array('bootstrap', 'jquery-ui-slider'));
	
	wp_enqueue_script('admin-number-slider-js', KIDS_ADMIN_JS_URI . '/jquery.mls_slider.js', array('jquery','jquery-ui-slider'));
	wp_enqueue_script('admin-script-js2', KIDS_ADMIN_JS_URI . '/admin-main.js', array('jquery','media-upload', 'thickbox', 'bootstrap'));

	/* Ajax in administration panel */
	wp_enqueue_script('admin-ajax-script', KIDS_ADMIN_JS_URI . '/admin-ajax.js', array( 'jquery', 'media-upload', 'thickbox','bootstrap')); 
	wp_localize_script('admin-ajax-script', ThemeSetup::HOOK . '_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
	                                                                           'theme_url' => get_bloginfo('template_url'),
	                                                                            ThemeSetup::HOOK . '_admin_ajax_nonce' => wp_create_nonce(ThemeSetup::HOOK . '-admin-ajax-nonce')));
}

