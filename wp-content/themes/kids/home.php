<?php get_header(); ?>

<?php 
    $featured_widget_on = $featured_post_id = $homepage_posts_ids = null;
    
    if($featured_on = Data()->isOn('mi_home.featured_section_switch')){
        $featured_widget_on = Data()->isOn('mi_home.featured_section_widget_switch');
        $featured_source_type = Data()->getMain('mi_home.featured_section_source_switch');
        $featured_readmore_link_show = Data()->isOn('mi_home.featured_section_readmore_switch');
        $featured_section_full = Data()->isOn('mi_home.featured_section_full');
        
        if($featured_source_type == 'post'){
            $featured_item_id = Data()->getMain('mi_home.featured_section_post');
            if(isset($featured_item_id)) {
                $featured_item = get_post($featured_item_id);
            }else{
                //Featured post is not set. Get First Available post
                $featured_item = $post;
                $featured_item_id = $post->ID;
            }
        }
        else
        {//we are dealing with pages
            $featured_item_id = Data()->getMain('mi_home.featured_section_page');
            if(isset($featured_item_id)) {
                $featured_item = get_page($featured_item_id);
            }else{
                //Featured post is not set. Get First Available post
                $featured_item = $post;
                $featured_item_id = $post->ID;
            }
        }
    }
    /* Middle section of the page */
    $homepage_cagetory_id = Data()->getMain('mi_home.content_section_category');
    $homepage_cagetory_id = $homepage_cagetory_id == '' || empty($homepage_cagetory_id) ? null : $homepage_cagetory_id;
    $readmore  = Data()->getMain('mi_blog.readmore');
    
    $is_slider_off  = Data()->isOn('mi_home.slider_hide_switch');
    $middle_area_on = Data()->isOn('mi_home.middle_switch');
?>

<div id="content" class="home">
<?php if(!$is_slider_off) : ?>
    <?php 
        mls_renderSlider(Data()->getSldr(), true); //generates whole slider section including Scrolling categories if activated!!!
    ?>
<?php else: ?>
   <style type="text/css">
        body {
            background: none !important;
        }
    </style>    
<?php endif; ?>
    <?php if($featured_on): ?>
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