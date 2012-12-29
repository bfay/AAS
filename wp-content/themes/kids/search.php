<?php 
    $readmore  = Data()->getMain('mi_blog.readmore');
    $hide_cats = Data()->getMain('mi_blog.hide_cats');
    
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
    global $query_string;
    query_posts( $query_string . '&order=DESC&orderby=date&paged='.get_query_var('paged').'&cat='.implode(',', $hide_cats_tmp) );
?>
 <div id="content">
        
        <div class="wrap">
            <div class="c-8 divider">
            
                <div class="post-list blog-posts">
                <?php if(have_posts()): ?>
                <?php 
                    $queryq = strip_tags(trim($_GET['s']));
                    $queryq = apply_filters( 'get_search_query', $queryq );
    		        $queryq = esc_attr( $queryq );
                ?>
                    <h2><?php printf( __( 'Search Results for: %s', 'kids_theme' ), '<span>' . $queryq. '</span>' ); ?></h2>
                    <?php while (have_posts()) : the_post(); ?>    
                        <div class="post post-<?php the_ID(); ?>">
                            <?php if(has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>" class="image">
                            	<span class="post-image-mask"></span>
                                <?php the_post_thumbnail('blog-thumb'); ?>
                            </a>
                            <?php endif; ?>
                            <h2 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                            <div class="excerpt">
                                <p><?php the_excerpt(); ?> </p>
                            </div>
                            <p class="actions">
                            	<a href="<?php the_permalink() ?>" class="read-more"><?php echo $readmore; ?><span class="circle-arrow"></span></a>
                            </p>
                        </div><!-- end post -->
                    <?php endwhile; ?>       
               <?php else : ?>
                    <h2><?php _e('Nothing Found', 'kids_theme') ?></h2>
               <?php endif; ?>  
                           
             <?php wp_pagenavi(array('class'=>'pagination','options'=> array('pages_text'=>' ',
                                                            'first_text'    => '',
                    										'last_text'     => '',
                                                            'always_show' => false,
                                                            'use_pagenavi_css'=>false,
                                                            'prev_text'     => '<span class="circle-arrow-left"></span>'. __('Previous', 'kids_theme'),
                    										'next_text'     => '<span class="circle-arrow"></span>'. __('Next', 'kids_theme'),
                                       ))
                                ); ?>
                
                </div><!-- end post-list -->
            </div>
             <?php get_sidebar(); ?>
        </div><!-- end wrap -->
    </div><!-- end content -->
<?php get_footer();?>