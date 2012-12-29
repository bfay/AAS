<div id="sidebar" class="c-4 sidebar">
<?php //if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("general_sidebar") ) : ?>
<?php global $post; if ( !function_exists('dynamic_sidebar') || !mls_custom_dynamic_sidebar($post->ID, "general_sidebar") ) : ?>
        <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->
        <div class="widget widget-latest-posts">
            <h3 class="widget-title"><?php _e('Categories', ThemeSetup::HOOK . '_theme'); ?></h3>
            <ul>
               <?php wp_list_categories('show_count=1&title_li='); ?>
            </ul>
        </div>
        <div class="widget widget-latest-posts">
            <h3 class="widget-title"><?php _e('Pages', ThemeSetup::HOOK . '_theme'); ?></h3>
            <ul>
              <?php wp_list_pages( array('title_li' => '')); ?> 
            </ul>
        </div>
         <div class="widget widget-latest-posts">
        	<h3 class="widget-title"><?php _e('Archives', ThemeSetup::HOOK . '_theme'); ?></h3>
        	<ul>
        		<?php wp_get_archives('type=monthly'); ?>
        	</ul>
         </div>    
 <?php endif; ?>  
</div>