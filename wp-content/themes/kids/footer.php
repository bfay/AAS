    <div id="footer">
        <div class="wrap">
            <div class="c-3">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_sidebar1") ) : ?>
                    <?php endif; ?>
            </div>
            <div class="c-3">
                     <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_sidebar2") ) : ?>
                     <?php endif; ?>
            </div>
            <div class="c-3">
                 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_sidebar3") ) : ?>
                 <?php endif; ?>
            </div>
            <div class="c-3">
                  <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("footer_sidebar4") ) : ?>
                  <?php endif; ?>
            </div>
        </div><!-- end wrap -->
    
        <div id="sub-footer" class="clearfix">
            <?php if(Data()->isOn('mi_footer.mi_copyright_switch')): ?>
                    <p id="copyright">&copy; <?php Data()->echoMain('mi_footer.mi_copyright'); //echo date("Y"); echo " "; bloginfo('name'); ?></p>
            <?php endif; ?>     
                 
                    <ul class="subfooter-menu">
                    <?php if(Data()->isOn('mi_footer.mi_terms_switch')): ?>
                        <li><a href="<?php Data()->echoMain('mi_footer.mi_terms'); ?>" title="Terms &amp; Conditions">Terms &amp; Conditions</a></li>
                    <?php endif; ?>
                    <?php if(Data()->isOn('mi_footer.mi_copyright_link_switch')): ?>    
                        <li><a href="<?php Data()->echoMain('mi_footer.mi_copyright_link'); ?>" title="Privacy Policy">Privacy Policy</a></li>
                    <?php endif; ?>    
                     <?php if(Data()->isOn('mi_footer.mi_sitemap_switch')): ?>    
                        <li><a href="<?php Data()->echoMain('mi_footer.mi_sitemap_link'); ?>" title="<?php _e('Site Map', 'kids_theme'); ?>">Site Map</a></li>
                     <?php endif; ?>   
                    </ul>
        </div><!-- end sub-footer -->
    </div><!-- end footer -->
    <?php 
        wp_footer(); 
    
        $custom_js  = Data()->getMain('mi_misc.mi_custom_js'); 
        $custom_js  = stripslashes($custom_js);
    ?>
    <!-- Custom JS -->
    <script type="text/javascript">
    <!--//--><![CDATA[//><!--
        <?php echo $custom_js; ?>
    //--><!]]>
    </script>
</body>
</html>      