<?php
/**
 *  Template Name: Blog - One Column template (Full Width List)
 *  Description: Show All post ordered by date. Mosr Recent on the top
 *
 *  @package Aislin
 *  @subpackage Kids
 */
?>

<?php 
    $show_meta  = Data()->isOn('mi_blog.mi_blog_meta_switch');
    $hide_cats = Data()->getMain('mi_blog.hide_cats');
    $readmore  = Data()->getMain('mi_blog.readmore');
    
    $hide_cats_tmp = array();
    if($hide_cats){
        foreach ($hide_cats as $value) {
            $hide_cats_tmp[] = (string)($value * (-1));
        }
    }
?>

<?php get_header();?>
<?php get_template_part('template-part-intro-featured-ribbon'); ?>
<?php 
  global $wp_query;
    $args = array( 'post_type' => 'post', 'order'=>'DESC', 'orderby'=>'date', 
    			   'paged' => get_query_var('paged'),
                   'cat' => implode(',', $hide_cats_tmp)    
                  );
    query_posts( $args );
?>

<div id="content">
        
        <div class="wrap">
            <div class="c-12">
             <?php if (have_posts()) : ?>
                <ul class="portfolio-menu">
                    <?php $i = 0; while (have_posts()) : the_post(); $i++; ?>                   
                    <li class="c-12 big-list clearfix">
                        <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <h4><?php echo get_post_meta($post->ID, '_post_subtitle', true); ?></h4>
                         <?php if(has_post_thumbnail()) : 
                            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'theme-gallery-photo');?>
                            <p class="image">
                                <a href="<?php echo $large_image_url[0]; ?>" rel="example_group" title="<?php the_title_attribute(); ?>">
                                    <span class="gallery-big-list-mask"></span>
                                    <!--<img height="307" width="627" title="" alt="" src="assets/gallery-big-list-assets-2.jpg" />-->
                                    <?php the_post_thumbnail('blog-big-list'); ?>
                                </a>
                            </p>
                        <?php endif; ?>    
                        <div class="excerpt">
                            <?php mls_abstract(apply_filters('the_content', strip_shortcodes(get_the_content())), 75, true ); ?>
                        </div>
                        <a class="read-more" href="<?php the_permalink() ?>"><?php echo $readmore; ?><span class="circle-arrow"></span></a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            <?php  endif; ?> 
                 <?php wp_pagenavi(array('class'=>'pagination','options'=> array('pages_text'=>' ',
                                                        'first_text'    => '',
                                                        'last_text'     => '',
                                                        'always_show' => false,
                                                        'use_pagenavi_css'=>false,
                                                        'prev_text'     => '<span class="circle-arrow-left"></span>'. __('Previous', 'kids_theme'),
                                                        'next_text'     => '<span class="circle-arrow"></span>'. __('Next', 'kids_theme'),
                                   ))
                            ); ?>
           <?php wp_reset_query(); ?>                 
            
            </div>    
            </div><!-- end wrap -->
        
    </div><!-- end content -->
<?php get_footer(); ?>