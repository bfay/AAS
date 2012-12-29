<?php
/**
 *  Template Name: Site Map Template
 *  Description: A template for a Site Map Page
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
                <div class="page">
                <div class="c-6">
                    <h2><?php _e('Pages', 'kids_theme') ?></h2>
                     <?php $args = array(
                            'show_date'    => 'modified',
                            'date_format'  => get_option('date_format'),
                            'child_of'     => 0,
                            'exclude'      => '',
                            'title_li'     => '',
                            'echo'         => 0,
                            'sort_column'  => 'menu_order, post_title',
                            'post_status'  => 'publish');
                  
                    $pages = wp_list_pages($args);
                    $pages = '<ul class="bullets">' . $pages . '</ul>';
                    echo $pages;
                    ?>
                    <div class="example"></div>
                    
                     <h2><?php _e('Tags', 'kids_theme') ?></h2>
                     <?php 
                       $tags = get_tags();
                        $html = '<ul class="bullets">';
                        foreach ($tags as $tag){
                        	$tag_link = get_tag_link($tag->term_id);
                        			
                        	$html .= "<li><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
                        	$html .= "{$tag->name}</a></li>";
                        }
                        $html .= '</ul>';
                        echo $html;
                     ?>
                
                </div><!-- c-6 -->
                <div class="c-6">
                    <h2><?php _e('Categories', 'kids_theme') ?></h2>
                    <?php 
                        $args = array(
                                        'orderby'            => 'name',
                                        'order'              => 'ASC',
                                        'style'              => 'list',
                                        'show_count'         => 1,
                                        'hide_empty'         => 1,
                                        'use_desc_for_title' => 1,
                                        'child_of'           => 0,
                                        'hierarchical'       => true,
                                        'exclude'			 => '1',
                                        'title_li'           => '',
                                        'show_option_none'   => __('No categories', 'kids_theme'),
                                        'number'             => NULL,
                                        'echo'               => 0,
                                        'depth'              => 0,
                                        'current_category'   => 0,
                                        'pad_counts'         => 0,
                                        'taxonomy'           => 'category',
                                        'walker'             => 'Walker_Category' );
                        $categories = wp_list_categories($args);
                        
                        $categories = '<ul class="bullets">' . $categories . '</ul>';
                        echo $categories;
                    ?>
                     <div class="example"></div>
                    
                     <h2><?php _e('Posts', 'kids_theme') ?></h2>
                     
                     <?php $postsx = mls_get_posts(); 
                     
                     $html = '<ul class="bullets">';
                     
                     foreach ($postsx as $id => $value) {
                         $html .= '<li><a href="'.get_permalink($id).'">'.$value.'</a></li>';
                     }
                      $html .= '</ul>';
                     
                     echo $html;
                     ?>
                    
                </div><!-- c-6 -->
                </div><!--  end page -->
            </div>
        </div><!-- end wrap -->
    </div><!-- end content -->

<?php get_footer(); ?>