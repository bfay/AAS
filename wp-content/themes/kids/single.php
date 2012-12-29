<?php 
    $show_meta  = Data()->isOn('mi_blog.mi_blog_meta_switch');
    $hide_cats = Data()->getMain('mi_blog.hide_cats');
    $readmore  = Data()->getMain('mi_blog.readmore');
    get_header(); 
    get_template_part('template-part-intro-featured-ribbon'); 
?>
<div id="content">
        
        <div class="wrap">
            <div class="c-8 divider">
                <div class="post-list">
                <?php if(have_posts()): while (have_posts()) : the_post(); ?>    
                    <div class="post post-<?php the_ID(); ?>">
                        <h2 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                        <?php if($show_meta): ?>
                        <p class="meta">
                            <span><?php _e('Date','kids_theme'); ?>: <a class="date" title="<?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?></p>" href="<?php //echo get_month_link(get_the_time('Y'),get_the_time('m'))?>"><?php the_time('F j, Y') ?></a></span>
                            <span><?php _e('Author','kids_theme'); ?>: <a class="author" title="<?php the_author(); ?>" href="<?php echo get_the_author_meta('url'); ?>"><?php the_author(); ?></a></span>
                            <span class="categories"><?php _e('Categories','kids_theme'); ?>: <?php the_category(', ') ?></span>
                            <?php  if(Data()->isOn('mi_blog.comments_switch')) : ?>
                                <span class="comments"><a title="<?php _e('All comments for', 'kids_theme'); the_title(' '); ?>" href="<?php echo the_permalink().'#comments'; ?>"><?php comments_number('0', '1', '%'); ?></a></span>
                            <?php endif; ?>
                        </p>
                        <?php endif; ?>
                        <?php if(has_post_thumbnail()): ?>
                            <p class="image"> 
                            <?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'theme-gallery-photo'); ?>
                                <a rel="example_group" href="<?php echo $large_image_url[0] ?>" title="<?php the_title_attribute()?>">
                                    <?php the_post_thumbnail('blog-large'); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php the_content(); ?>
                         <p class="meta dashed">
                             <?php the_tags('<span class="tags">'.__('Tags', 'kids_theme').': ', ', ','</span>'); ?>
                        </p>
                       
                    </div><!-- end post -->
                    
                    <?php if(Data()->isOn('mi_blog.comments_switch')) : comments_template(); endif; ?>
                    
                 <?php endwhile; ?>
              <?php endif; ?>       
                 
                </div><!-- end post-list -->
            </div>
             <?php get_sidebar('blog'); ?>
        </div><!-- end wrap -->
        
    </div><!-- end content -->
<?php get_footer(); ?>