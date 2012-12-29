<?php get_header(); ?>

<?php 
    $featured_widget_on = $featured_post_id = $homepage_posts_ids = null;
    
    if($featured_on = Data()->isOn('mi_home.featured_section_switch')){
        $featured_widget_on = Data()->isOn('mi_home.featured_section_widget_switch');
        $featured_post_id = Data()->getMain('mi_home.featured_section_post');
        if(isset($featured_post_id)) {
            $featured_post = get_post($featured_post_id);
        }else{
            //Featured post is not set. Get First Available post
            $featured_post = $post;
            $featured_post_id = $post->ID;
        }
    }
    $homepage_cagetory_id = Data()->getMain('mi_home.content_section_category');
    $homepage_cagetory_id = $homepage_cagetory_id == '' || empty($homepage_cagetory_id) ? null : $homepage_cagetory_id;
    $readmore  = Data()->getMain('mi_blog.readmore');
?>

<div id="content" class="home">
    <div id="slider">
    <?php 
        mls_renderSlider(Data()->getSldr(), true); //generates whole slider section including Scrolling categories if activated!!!
    ?>
    </div> <!-- #slider -->
    
    <?php if($featured_on): ?>
    <div id="featured">
        <div class="wrap">
               
                <div class="<?php echo ($featured_widget_on ? 'c-8':'c-12') ?>">
                    <div class="page">
                        <h2><?php echo strtoupper($featured_post->post_title); ?></h2>
                        <p><?php mls_abstract(strip_shortcodes($featured_post->post_content), 45, true); ?></p>
                        <a href="<?php echo get_permalink($featured_post_id); ?>" class="read-more"><?php echo $readmore ?><span class="circle-arrow"></span></a>
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
    </div><!-- END featured -->
    <?php endif; ?>
    
     <div id="container-middle">
        <div class="wrap">
        
        <?php 
            $args = array( 'numberposts' => 3, 
            				'order'=> 'DESC', 
            				'orderby' => 'rand', 
            				'hide_empty'=>1,
                            'suppress_filters'=>false );
            if($homepage_cagetory_id) $args['category'] = $homepage_cagetory_id;
            $postslist = get_posts( $args );
        ?>
        <?php foreach ($postslist as $post) : setup_postdata($post); ?> 
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
        
</div><!-- div#content.home -->
<?php get_footer(); ?>