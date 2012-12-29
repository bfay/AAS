<?php 
$theme = ThemeSetup::getInstance()->getThemeDataObj();

$logo_defined  = (Data()->getMain('mi_look_feel.mi_logo') != '' && Data()->getMain('mi_look_feel.mi_logo') != null) ? Data()->getMain('mi_look_feel.mi_logo') : false;
$logo_h = (Data()->getMain('mi_look_feel.logo_height') !='' && Data()->getMain('mi_look_feel.logo_height') != null) ? Data()->getMain('mi_look_feel.logo_height') : false;
$logo_w = (Data()->getMain('mi_look_feel.logo_width') !='' && Data()->getMain('mi_look_feel.logo_width') != null) ? Data()->getMain('mi_look_feel.logo_width') : false;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="keywords" content="" /> 
    <link rel='stylesheet' id='layout-css'  href="<?php echo KIDS_URI;?>/style.css" type='text/css' media='all' />
    <link rel='stylesheet' id='colors-css'  href="<?php echo KIDS_CSS_URI . '/color-shemes/'.Data()->getMain('mi_look_feel.color_sheme').'/'.Data()->getMain('mi_look_feel.color_sheme') ?>" type='text/css' media='all' />
    <style type="text/css">
        h1 a {
            background: none !important;    
        }
    </style>
</head>

<body>
    <div id="header">
        <div class="wrap">
            <!-- logo -->
            
            <h1><a href="<?php echo get_option('home'); ?>/"><?php if($logo_defined): ?><img <?php echo ($logo_h ? 'height="'.$logo_h.'"' : '' ); ?> <?php echo ($logo_h ? 'width="'.$logo_w.'"' : '' ); ?> src="<?php echo $logo_defined; ?>" title="<?php bloginfo('name'); ?>"/><?php endif; ?></a></h1>
                <!-- Main Navigation -->
                <!-- Main Navigation End -->
        </div> <!-- .wrap -->
    </div> <!-- #header end -->

 <div id="intro">
        <div class="wrap">
            <div>
               <h1><?php Data()->echoMain('mi_misc.mi_updating_teaser_text') ?></h1>
            </div>
        </div><!-- end wrap -->
    </div><!-- end intro -->

 <div id="content">
        
        <div class="wrap">
            <div class="c-12 page-404">
                <h3><?php Data()->echoMain('mi_misc.mi_updating_title') ?></h3>
                <p><?php Data()->echoMain('mi_misc.mi_updating_text') ?><p>
            </div>
        </div><!-- end wrap -->
    </div><!-- end content -->
    
    <div id="footer">
    </div><!-- end footer -->
</body>
</html>      