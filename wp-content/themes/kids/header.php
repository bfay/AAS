<?php 
$custom_css = Data()->getMain('mi_misc.mi_custom_css'); 
$logo_defined  = (Data()->getMain('mi_look_feel.mi_logo') != '' && Data()->getMain('mi_look_feel.mi_logo') != null) ? Data()->getMain('mi_look_feel.mi_logo') : false;
$logo_h = (Data()->getMain('mi_look_feel.logo_height') !='' && Data()->getMain('mi_look_feel.logo_height') != null) ? Data()->getMain('mi_look_feel.logo_height') : false;
$logo_w = (Data()->getMain('mi_look_feel.logo_width') !='' && Data()->getMain('mi_look_feel.logo_width') != null) ? Data()->getMain('mi_look_feel.logo_width') : false;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.esc_html($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?></title>

    <!-- Site Description -->
	<meta name="description" content="<?php echo dynamic_meta_description(); ?>" />
    
    <!-- WP Tags goes here -->
	<meta name="keywords" content="<?php echo csv_tags(); ?>" />
	
	<link rel="stylesheet"  href="http://www.alertsforallergies/wp-content/themes/reverie/css/foundation.css" type="text/css" media="all">
	
	
	    
    <!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo Data()->getMain('mi_look_feel.mi_favicon'); ?>" type="image/x-icon" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); //This Code should be elsewhere... ?>
    <!-- Custom CSS and style related to a logo -->
    <style type="text/css">
        <?php echo $custom_css; ?>
        <?php if($logo_defined) {// User defined logo ?>       
                h1 a { background: none !important; }
         <?php } ?>    
    </style>
    <script type="text/javascript">
       /* <![CDATA[ */
        var settings = {'themeurl':'<?php bloginfo('template_url'); ?>'};
        /* ]]> */   
    </script>
    
	<?php wp_head(); ?>
    <?php 
        if(Data()->isOn('mi_google_analitics.google_analitics_switch')): 
           Data()->echoMain('mi_google_analitics.google_analitics');
        endif; 
    ?>
</head>

<body <?php body_class(); ?>>
	<div id="header">
        <?php if(defined('MLS_SHOWCASE') && MLS_SHOWCASE) :?>
        
            <ul id="nav">
                <li><a class="Green-Blue" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/green-blue/green-blue.css" href="#"></a></li>
                <li><a class="Grey-Red" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/grey-red/grey-red.css" href="#"></a></li>
                <li><a class="Orange-Red" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/orange-red/orange-red.css" href="#"></a></li>
                <li><a class="Pink-Grey" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/pink-grey/pink-grey.css" href="#"></a></li>
                <li><a class="Purple-Brown" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/purple-brown/purple-brown.css" href="#"></a></li>
                <li><a class="Turquise-Black" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/turquise-black/turquise-black.css" href="#"></a></li>
                <li><a class="Turquise-Grey" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/turquise-grey/turquise-grey.css" href="#"></a></li>
                <li><a class="Turquise-Orange" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/turquise-orange/turquise-orange.css" href="#"></a></li>
                <li><a class="Yellow-Orange" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/yellow-orange/yellow-orange.css" href="#"></a></li>
                <li><a class="Yellow-Purple" rel="<?php echo KIDS_CSS_URI; ?>/color-shemes/yellow-purple/yellow-purple.css" href="#"></a></li>
            </ul>
        <?php endif; ?>
		<div class="wrap">
            <!-- logo -->
            
			<h1><a href="<?php echo get_option('home'); ?>/"><?php if($logo_defined): ?><img <?php echo ($logo_h ? 'height="'.$logo_h.'"' : '' ); ?> <?php echo ($logo_h ? 'width="'.$logo_w.'"' : '' ); ?> src="<?php echo $logo_defined; ?>" title="<?php bloginfo('name'); ?>"/><?php endif; ?></a></h1>
		        <!-- Main Navigation -->
                <?php echo mls_get_navigation_menu(); ?>
                <!-- Main Navigation End -->
        </div> <!-- .wrap -->
	</div> <!-- #header end -->