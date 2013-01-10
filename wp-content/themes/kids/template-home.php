<?php
/**
 *  Template Name: Custom Home 
 *  Description: Homepage
 *

 */
?>

<?php get_header(); ?>

<div id="content" class="home">
<?= do_shortcode('[layerslider id="1"]'); ?>
</div>
    <div class="opt-in"><img src="http://www.allergiesatschool.com/wp-content/themes/kids/images/opt-in.png" title="opt-in form"/>	
    </div>

    <div id="featured">
        <div class="wrap">
               
                <div class="<?php echo ($featured_widget_on ? 'c-8':'c-12') ?>">
                    <div class="page">
                        <h2><a title="<?php the_title_attribute(); ?>" href="<?php echo get_permalink($featured_item->ID); ?>"><?php echo strtoupper($featured_item->post_title); ?></a></h2>
                        <?php if($featured_section_full) :?>
                           <?php echo apply_filters('the_content', $featured_item->post_content); ?> 
                        <?php else : ?>
                            <p><?php echo mls_the_excerpt_max_charlength(strip_shortcodes($featured_item->post_content), 250, true); ?></p>
                        <?php endif;?> 
                        
                        <?php if($featured_readmore_link_show) : ?>
                        <a href="<?php echo get_permalink($featured_item_id); ?>" class="read-more"><?php echo $readmore ?><span class="circle-arrow"></span></a>
                        <?php endif; ?> 
                    </div>
                </div>
                <?php if($featured_widget_on): ?>
                <div class="c-4">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("home_sidebar") ) : ?>
                        <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->
                        No Widget 
                   <?php endif; ?>  
               </div>    
               <?php endif; ?>
               
        </div><!-- end wrap -->  
        <div class="clearfix"></div>                
    </div><!-- END featured -->
    <?php endif; ?>
    
    <?php if($middle_area_on) :?>
     <div id="container-middle">
        <div class="wrap">
        
        <?php 
        if(Data()->getMain('mi_home.content_section_source_switch') == 'mpost'){
            $args = array( 'numberposts' => 3, 
            				'order'=> 'DESC', 
            				'orderby' => 'post_date', 
            				'hide_empty'=>1,
                            'suppress_filters'=>false );
            if($homepage_cagetory_id) $args['category'] = $homepage_cagetory_id;
            
            $postslist = get_posts( $args );
        }else if (Data()->getMain('mi_home.content_section_source_switch') == 'mpage'){
            $args = array('include'=>Data()->getMain('mi_home.content_section_pages'), 
            			  'number'=>3,
                          'sort_column'=>'menu_order',
                          'sort_order'=>'ASC');
            $postslist = get_pages($args);
        }   
        ?> 
        <?php if(!isset($postslist) || !is_array($postslist)){$postslist = array();} foreach ($postslist as $post) : setup_postdata($post); ?> 
                <div class="c-4">
                    <h3><?php the_title(); ?></h3>
                    <h4><?php echo the_subtitle();//get_post_meta($post->ID, '_post_subtitle', true); ?></h4>
                    
                    <?php if(has_post_thumbnail()): 
                        $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'theme-gallery-photo');
                    ?>
                    <div class="mask">
                        <a title="<?php the_title_attribute(); ?>" href="<?php echo $large_image_url[0]; ?>" rel="example_group">
                            <span class="middle-frame-mask"></span>
                            <?php the_post_thumbnail('theme-small-thumb'); ?>
                        </a>    
                    </div>
                     <?php endif; ?>
                    <p><?php echo the_excerpt();?></p>
                    <a href="<?php the_permalink(); ?>" class="read-more"><?php echo $readmore ?><span class="circle-arrow"></span></a>
                 </div>
         <?php endforeach; ?>     
                 
        </div><!-- end wrap -->         
    </div> <!-- end container-middle -->
  <?php endif; ?>  
        
</div><!-- div#content.home -->
<?php get_footer(); ?>