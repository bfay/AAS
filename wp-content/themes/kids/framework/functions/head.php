<?php
/* 
	All staff related to a header of the page
*/

/* Administration panel does NOT need this. Skip it. */

if(is_admin()){
	return;
}

$theme = ThemeSetup::getInstance()->getThemeDataObj();

if($theme->isOn('mi_misc.mi_updating_in_progress')){ 
    /*Ako nije login*/
    if(preg_match('/wp-login.php/', $_SERVER['SCRIPT_NAME'])==0 && !current_user_can('edit_posts')){
        //goto under construction page
       // header("Location:" . KIDS_URI . '/template-under-construction.php');
        include_once (KIDS_DIR . '/template-under-construction.php');
        exit;    
    }
}


add_action('wp_print_scripts', 'add_scripts');
add_action('wp_print_styles', 'add_styles');

function add_scripts(){
	
    //$kids_ua = getBrowser();
    /* This script should be loaded always */
    
    //Fancybox
    wp_enqueue_script(ThemeSetup::HOOK.'fancybox-js', KIDS_JS_URI . '/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'), '1.3.4');
	//Easing
	wp_enqueue_script(ThemeSetup::HOOK . 'jquery-easing-js', KIDS_JS_URI . '/jquery.easing.1.3.js', array('jquery'), '1.3.0');
	//wp_enqueue_script(ThemeSetup::HOOK . '-common-js',KIDS_JS_URI .'/common.js', array('jquery'));
	
	// Image Preloader
    wp_enqueue_script(ThemeSetup::HOOK.'-preloaderjs', KIDS_JS_URI.'/preloader/jquery.preloader.js', array('jquery'));
    
	//Main Js File
    wp_enqueue_script(ThemeSetup::HOOK.'-mainjs', KIDS_JS_URI.'/main.js', array('jquery', ThemeSetup::HOOK.'fancybox-js', ThemeSetup::HOOK.'-preloaderjs'));
        
    // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
    wp_localize_script( ThemeSetup::HOOK.'-mainjs', ThemeSetup::HOOK . 'Ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                                                                           'ajax_nonce' => wp_create_nonce(ThemeSetup::HOOK . '-ajax-nonce-xxx')
                                                                          ));
                                                                          
    if(defined('MLS_SHOWCASE') && MLS_SHOWCASE){
        wp_enqueue_script(ThemeSetup::HOOK.'-cookie', KIDS_JS_URI.'/jquery.cookie.js', array('jquery'));
        wp_enqueue_script(ThemeSetup::HOOK.'-color-witcher', KIDS_JS_URI.'/color-switcher.js', array('jquery', ThemeSetup::HOOK.'-cookie'));
    }                                                                      

  //  if(is_page()){
    if(is_page_template('template-contact.php')){ // Contact page specific js
         /* Google Maps Staff */
       $theme = ThemeSetup::getInstance()->getThemeDataObj(); 
        if($theme->isOn('mi_gmaps.mi_gmaps_switch')){
            wp_enqueue_script(ThemeSetup::HOOK . '-google-maps-api-js', 'http://maps.google.com/maps/api/js?sensor=false');
            wp_enqueue_script(ThemeSetup::HOOK . '-googlemap-js', KIDS_JS_URI .'/googlemap.js', array('jquery', ThemeSetup::HOOK . '-google-maps-api-js'));
	
            wp_localize_script(ThemeSetup::HOOK . '-googlemap-js', ThemeSetup::HOOK . 'Google', array('lat'=>$theme->getMain('mi_gmaps.mi_gmaps_lat'), 
            																					   'long'=>$theme->getMain('mi_gmaps.mi_gmaps_long'),
                                                                                                   'zoom'=>$theme->getMain('mi_gmaps.mi_gmaps_zoom')));
        }
   }
   // }
  
   if(defined('FORCE_PIECEMAKER_SLIDER') && FORCE_PIECEMAKER_SLIDER){
         wp_enqueue_script( ThemeSetup::HOOK . '_'.$slider_type, KIDS_URI . '/framework/library/piecemaker/scripts/swfobject/swfobject.js'); 
   }
    
    if(is_home()){
        //Get Slider type
        
        if($slider_type = Data()->getCurrentSlider()){
            switch ($slider_type){
                case 'piece_slider':{
                   wp_enqueue_script( ThemeSetup::HOOK . '_'.$slider_type, KIDS_URI . '/framework/library/piecemaker/scripts/swfobject/swfobject.js'); 
                    break;
                }
            }
        }
    }
}

/* Add Common styling */
function add_styles(){
	$hook = 'kids';
	global $wp_styles;
	//Get Main Css File
	wp_register_style($hook . 'style', KIDS_URI . '/style.css');
	wp_register_style($hook . 'style', get_bloginfo('stylesheet_url'));
	
	wp_enqueue_style($hook . 'style');

	//IE Fixes
    wp_register_style( $hook . '-ie7-fix', KIDS_CSS_URI . '/ie7.css' );
    $wp_styles->add_data( $hook . '-ie7-fix', 'conditional', 'IE 7' );
    wp_enqueue_style($hook . '-ie7-fix');

    wp_register_style( $hook . '-ie8-fix', KIDS_CSS_URI . '/ie8.css' );
    $wp_styles->add_data( $hook . '-ie8-fix', 'conditional', 'IE 8' );
	wp_enqueue_style($hook . '-ie8-fix');

	//Get Color Sheme
	wp_register_style($hook . 'color-sheme', 
	                  KIDS_CSS_URI . '/color-shemes/'.Data()->getMain('mi_look_feel.color_sheme').'/'.Data()->getMain('mi_look_feel.color_sheme').'.css');
	wp_enqueue_style($hook . 'color-sheme');

	//Fancy Box CSS
	wp_enqueue_style($hook.'fancybox-css', KIDS_JS_URI . '/fancybox/jquery.fancybox-1.3.4.css',array(), '1.3.4');
	
	if(defined('MLS_SHOWCASE') && MLS_SHOWCASE){
	    wp_enqueue_style($hook.'color-switcher-css', KIDS_CSS_URI . '/select_color.css',array());
	}	
}