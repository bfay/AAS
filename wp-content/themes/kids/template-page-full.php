<?php
/**
 *  Template Name: Full Width Template 
 *  Description: A Page without sidebar
 *
 *  @package Aislin
 *  @subpackage Kids
 */
?>

<?php get_header(); ?>
<?php get_template_part('template-part-intro-featured-ribbon'); ?>

   <div id="content">
        
        <div class="wrap">
            <div class="c-12">
            
                <div class="page page-content">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <h2><?php the_title(); ?></h2>
                 
                    <?php if ( has_post_thumbnail() ): the_post_thumbnail('medium', array('class'=>'alignleft')); endif; ?>
                    <?php the_content(); ?>
                    <?php endwhile; endif; ?>
                </div><!--  end entry -->
                
            </div>
        </div><!-- end wrap -->
    </div><!-- end content -->
<?php get_footer(); ?>