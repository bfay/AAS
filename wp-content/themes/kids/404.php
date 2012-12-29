<?php 
    $page_not_found = Data()->getMain('mi_misc.mi_404');
    get_header(); 
    get_template_part('template-part-intro-featured-ribbon'); ?>
   <div id="content">
        <div class="wrap">
            <div class="c-12 page-404">
                <h3><?php _e('Page not found', 'kids_theme')?></h3>
                <p><?php echo $page_not_found; ?><p>
            </div>
        </div><!-- end wrap -->
    </div><!-- end content -->
<?php get_footer(); ?>