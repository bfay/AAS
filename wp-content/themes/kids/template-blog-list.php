<?php
/**
 *  Template Name: Blog - Blog list with standard post images 
 *  Description: A Blog Page without sidebar
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
    
    get_header();
    get_template_part('template-part-intro-featured-ribbon');
    
    global $wp_query;
    $args = array( 'post_type' => 'post', 'order'=>'DESC', 'orderby'=>'date', 
    			   'paged' => get_query_var('paged'),
                   'cat' => implode(',', $hide_cats_tmp)    
                  );
    query_posts( $args );
?>
 <div id="content">
        <div class="wrap">
            <div class="c-12 divider">
                <div class="post-list blog-posts">
                <?php if(have_posts()): while (have_posts()) : the_post(); ?>    
                    <div class="post post-<?php the_ID(); ?>">
                        <?php if(has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>" class="image">
                        	<span class="post-image-mask"></span>
                            <?php the_post_thumbnail('blog-thumb'); ?>
                        </a>
                        <?php endif; ?>
                        <h2 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                        <?php if($show_meta): ?>
                        <p class="meta">
                            <span><?php _e('Date','kids_theme'); ?>: <a class="date" title="<?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?></p>" href="<?php //echo get_month_link(get_the_time('Y'),get_the_time('m'))?>"><?php the_time('F j, Y') ?></a></span>
                            <span><?php _e('Author','kids_theme'); ?>: <a class="author" title="<?php the_author(); ?>" href="<?php echo get_the_author_meta('url'); ?>"><?php the_author(); ?></a></span>
                            <span class="categories"><?php _e('Categories','kids_theme'); ?>: <?php the_category(', ') ?></span>
                            <?php if(Data()->isOn('mi_blog.comments_switch')) : ?>
                            <span class="comments"><a title="<?php _e('All comments for', 'kids_theme'); the_title(' '); ?>" href="<?php echo the_permalink().'#comments'; ?>"><?php comments_number('0', '1', '%'); ?></a></span>
                            <?php endif; ?>
                        </p>
                        <?php endif; ?>
                        <div class="excerpt">
                            <p><?php the_excerpt(); ?> </p>
                        </div>
                        <p class="actions">
                        	<a href="<?php the_permalink() ?>" class="read-more"><?php echo $readmore; ?><span class="circle-arrow"></span></a>
                        </p>
                    </div><!-- end post -->
             <?php endwhile; endif; ?>       
                              
             <?php wp_pagenavi(array('class'=>'pagination','options'=> array('pages_text'=>' ',
                                                            'first_text'    => '',
                    										'last_text'     => '',
                                                            'always_show' => false,
                                                            'use_pagenavi_css'=>false,
                                                            'prev_text'     => '<span class="circle-arrow-left"></span>'. __('Previous', 'kids_theme'),
                    										'next_text'     => '<span class="circle-arrow"></span>'. __('Next', 'kids_theme'),
                                       ))
                                ); 
               wp_reset_query(); 
             ?>
                
                </div><!-- end post-list -->
            </div>
        </div><!-- end wrap -->
    </div><!-- end content -->
<?php get_footer();?>