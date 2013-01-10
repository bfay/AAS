<?php 
/**
 *  Template part: Ribbont at all inner pages 
 *
 *  @package Aislin
 *  @subpackage Kids
 */
?>
    <div id="intro">
        <div class="wrap">

            <?php if(Data()->isOn('mi_social.switch')): ?>
            <div class="row">
                <div class="widget widget-social social-right four columns">
                    <ul>
                        <li><h3 class="widget-title"><?php Data()->echoMain('mi_social.title')?></h3></li>
                        <?php if(Data()->getMain('mi_social.twitter')): ?><li><a target="_blank" class="twitter-intro" title="Twitter Profile" href="http://twitter.com/<?php Data()->echoMain('mi_social.twitter'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.facebook')): ?><li><a target="_blank" class="facebook-intro" title="Facebook Profile" href="<?php Data()->echoMain('mi_social.facebook'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.linkedin')): ?><li><a target="_blank" class="social-intro" title="LinkedIn Profile " href="<?php Data()->echoMain('mi_social.linkedin'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.pinterest')): ?><li><a target="_blank" class="pin-intro" title="Pintrest Profile " href="<?php Data()->echoMain('mi_social.pinterest'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.youtube')): ?><li><a target="_blank" class="youtube-intro" title="Youtube channel" href="<?php Data()->echoMain('mi_social.youtube'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.googleplus')): ?><li><a target="_blank" class="google-intro" title="Google Plus" href="<?php Data()->echoMain('mi_social.googleplus'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.rss')): ?><li><a class="rss-intro" title="" href="<?php Data()->echoMain('mi_social.rss'); ?>"></a></li><?php endif;?>
                    </ul>
                 </div>
            </div>
        
            
            <div class="row wrap slider-container">
            <div class="my-slider eight columns">
<?= do_shortcode('[layerslider id="1"]'); ?>
</div>
<div class="opt-in four columns">
<img src="http://www.allergiesatschool.com/wp-content/themes/kids/images/opt-in.png">
</div>
            </div><!-- ! end row -->
                </div>
            <?php endif; ?>
            
        </div><!-- end wrap -->
    </div><!-- end intro -->