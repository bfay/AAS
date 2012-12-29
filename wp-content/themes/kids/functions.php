<?php
    if(!WP_DEBUG){ 
        error_reporting(0);
        ini_set('display_errors', false);
    }else{
       error_reporting(E_ALL);
       ini_set('display_errors', true); 
    }
        
    /* No direct Access */
    define('RUN_FOREST_RUN', true);
    
    /* Should we show/include Color Sheme Switcher. Is this showcase or for sale? */
    define('MLS_SHOWCASE', false);
    
    add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
        
    /* Class auto loading function */
     function kidsThemeAutoload($class) {
        $include_paths = array('/framework/','/framework/library/','/framework/widgets/','/framework/admin/');
        $path  = dirname(__FILE__);
        foreach ($include_paths as $inc_path) { 
            if(file_exists($path . $inc_path . $class . '.php')){
                include($path . $inc_path . $class . '.php');
            }
        }
    } 
    spl_autoload_register('kidsThemeAutoload'); 
    
    //Get Theme Instance 
    $kids_theme  = ThemeSetup::getInstance()->init(); 
    //Are we in the administration area?
    if(is_admin()){
        $admin_theme = AdminSetup::getInstance()->init(); 
    }
    
    if(WP_DEBUG){
        if($kids_theme->hasErrors()){
            echo '<pre>';
            echo "ERRORS ::: ThemeSetup->";
            var_dump( $kids_theme->errors);
            echo "ERRORS ::: ThemeADmin->";
            var_dump(ThemeAdmin()->errors);
            echo "ERRORS ::: ThemeOptions->";
            var_dump($kids_theme->getThemeDataObj()->errors);
            echo '</pre>';
        }
    }    