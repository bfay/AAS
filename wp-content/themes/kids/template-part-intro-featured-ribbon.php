<?php 
/**
 *  Template part: Ribbont at all inner pages 
 *
 *  @package Aislin
 *  @subpackage Kids
 */
?>
    <div id="intro">
        <div class="wrap">
        
        <?php $teaser_text = Data()->isOn('mi_header.teaser_switch'); ?>
        <?php 
            if($teaser_text){
               
                  $ribbon_teaser = '';
                  if(is_category()){
                      $ribbon_teaser = single_cat_title('', false);
                  }elseif (is_tag()){
                      $ribbon_teaser = single_tag_title('', false);
                      $ribbon_teaser = __('Tag', 'kids_theme') .': '. $ribbon_teaser;
                  }elseif (is_day()){
                      $ribbon_teaser = __('Archives for', 'kids_theme') . get_the_time('F jS, Y');
                  }elseif (is_month()){
                      $ribbon_teaser = single_month_title('', false);
                  }elseif (is_year()){
                      $ribbon_teaser = __('Archives for', 'kids_theme') . get_the_time('F, Y');
                  }elseif (is_author()){
                      $ribbon_teaser = __('Author Archive', 'kids_theme');
                  }elseif (isset($_GET['paged']) && !empty($_GET['paged'])){
                      $ribbon_teaser = __('Blog Archives', 'kids_theme');
                  }elseif(is_404()){
                      $ribbon_teaser = __('404 - Sorry', 'kids_theme');
                  }elseif (is_search()){
                      $ribbon_teaser = __('Search Result', 'kids_theme');
                  }
                  else{
                      $post = $posts[0]; // Hack. Set $post so that the_date() works. 
                      if(is_page()){
                          
                          if(mb_strlen($post->post_title) > 17){
                              $ribbon_teaser = '...';
                          }
                          $ribbon_teaser = mb_substr($post->post_title, 0, 17) . $ribbon_teaser;
                      }else
                      {
                          $category      = get_the_category($post->ID); 
                          if(!empty($category)){
                              $ribbon_teaser = $category[0]->cat_name;
                          }
                      }
                  }
            }     
        ?>
            <div>
               <?php if($teaser_text) :?> <h1><?php echo $ribbon_teaser; ?></h1><?php endif; ?>
                <?php $hide_breadcrumbs_text = Data()->isOn('mi_header.bread_crumbs_text_switch'); //title
                    if($hide_breadcrumbs_text) $mls_breadcrumbs_opts= array('title'=>''); else $mls_breadcrumbs_opts= '';
                ?>
                <?php if(Data()->isOn('mi_header.breadcrumbs_switch')) : breadcrumbs_plus($mls_breadcrumbs_opts); endif; ?>
            </div>
            
            <?php if(Data()->isOn('mi_social.switch')): ?>
            <div>
                <div class="widget widget-social">
                    <ul>
                        <li><h3 class="widget-title"><?php Data()->echoMain('mi_social.title')?></h3></li>
                        <?php if(Data()->getMain('mi_social.twitter')): ?><li><a target="_blank" class="twitter-intro" title="Twitter Profile" href="http://twitter.com/<?php Data()->echoMain('mi_social.twitter'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.facebook')): ?><li><a target="_blank" class="facebook-intro" title="Facebook Profile" href="<?php Data()->echoMain('mi_social.facebook'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.linkedin')): ?><li><a target="_blank" class="social-intro" title="LinkedIn Profile " href="<?php Data()->echoMain('mi_social.linkedin'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.pinterest')): ?><li><a target="_blank" class="pin-intro" title="Pintrest Profile " href="<?php Data()->echoMain('mi_social.pinterest'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.youtube')): ?><li><a target="_blank" class="youtube-intro" title="Youtube channel" href="<?php Data()->echoMain('mi_social.youtube'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.googleplus')): ?><li><a target="_blank" class="google-intro" title="Google Plus" href="<?php Data()->echoMain('mi_social.googleplus'); ?>"></a></li><?php endif;?>
                        <?php if(Data()->getMain('mi_social.rss')): ?><li><a class="rss-intro" title="" href="<?php Data()->echoMain('mi_social.rss'); ?>"></a></li><?php endif;?>
                    </ul>
                 </div>
            </div>
            <?php endif; ?>
            
        </div><!-- end wrap -->
    </div><!-- end intro -->