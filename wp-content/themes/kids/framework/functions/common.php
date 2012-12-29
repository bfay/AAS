<?php
/**
 * Common function used in frontend and backend
 */


/**
 * Handels all Ajax request from the Front. Contacts forms etc...
 * Enter description here ...
 */
function mls_ajax_handler(){
        $response = array('status'=>'success');
        check_ajax_referer(ThemeSetup::HOOK . '-ajax-nonce-xxx', ThemeSetup::HOOK . '_ajax_nonce');
        $action     = '';
        $sub_action = strip_tags(trim($_POST['subaction']));
        
        switch ($sub_action){
            
            case 'contact':{
                //Validation map. Fields with "*" are obligatory!
                $fields = array('name' => '*|v_text', 
                				'email' => '*|v_mail', 
    							'subject' => '-|v_text', 
    							'message' => '*|v_text', 
    							'website' => '-|v_url');
                
                if(defined('MLS_SHOWCASE') && !MLS_SHOWCASE){
                    $tm = new ThemeMailer($fields);
                    $resp = $tm->doMagic();
                
                    $response = $tm->getJsonResponse();
                }else{
                    $response['data']['msg'] = __('Sending E-mails not allowed', 'kids_theme');
                }
                break;
            }
            default :{
                $response = array('status' => 'error', 'data'=> array('msg'=>'Unknown request') );
            }
        }
    die(json_encode($response));
}


function mls_get_navigation_menu(){
    $menu = '';
    if(has_nav_menu('header-menu')){
         $menu = wp_nav_menu( array( 
    						'theme_location' => 'header-menu',
                            'container_id' => 'main-navigation',
                            'menu_class' => 'dd-menu',
                            'echo' => false
                          )); 
    }
    else{
        $menu = wp_list_pages( array(
                            	'depth'        => 2,
                            	'show_date'    => '',
                            	'date_format'  => get_option('date_format'),
                            	'child_of'     => 0,
                                'number'		=> 5,
                            	'title_li'     => '',
                            	'echo'         => 0,
                            	'sort_column'  => 'menu_order, post_title',
                            	'link_before'  => '',
                            	'link_after'   => '',
                            	'walker'       => '' ) );
        
        $menu = '<div id="main-navigation"><ul class="dd-menu">'.$menu.'</ul></div>';
    }
    return $menu;
}


/**
 * List of categories
 * @param array $params
 */
function mls_get_categories($params = array()){
    $params = wp_parse_args($params, array('type'    => 'post',
                                            'orderby' => 'name',
                                            'order'   => 'ASC',
                                            'hide_empty' => 0,
                                            'number' => 999,
                                            'taxonomy'   => 'category'));

    $data = array();
    $cats = get_categories($params);
    foreach ($cats as $cat) {
        $data[$cat->cat_ID] = $cat->name;
    }
    return $data;
}

function mls_get_galleries($params = array()){
    
    get_posts(array('post_type' => 'mlsgallery', 'post_status' => 'publish'));
    
    $params = wp_parse_args($params, array( 'post_status' => 'publish',
                                            'number' => 999,
    										'post_type' => 'mlsgallery'
                                            ));

    $data = array();
    $gals = get_posts($params);
    foreach ($gals as $g) {
        $data[$g->ID] = $g->post_title;
    }
    return $data;
}


/**
 * Enter description here ...
 * @param unknown_type $options
 */
function mls_get_pages($options = array()){
    $data = array();
    
    $default = array('sort_order' => 'ASC', 'sort_column' => 'menu_order');
    $options = wp_parse_args((array)$options, $default);
    
    $pages = get_pages($options);
    foreach ($pages as $page) {
        $data[$page->ID] = $page->post_title;
    }
    wp_reset_query();
    return $data;
}

function mls_get_posts($cat_id = '', $limit=-1){
    
    $data = array();
    $args = array( 'orderby' => 'post_date', 'order' => 'DESC', 'post_status'=>'publish', 'posts_per_page'=>$limit); 
    
    if($cat_id != ''){
        $args['category'] = $cat_id;
    }
         
    $posts = new WP_Query($args);
    if(!empty($posts->posts)){
        foreach ($posts->posts as $p){
            $data[$p->ID] = $p->post_title;
        }
    }
    wp_reset_query();
    wp_reset_postdata();
    return $data;
}


function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

// REMOVE THE WORDPRESS UPDATE NOTIFICATION FOR ALL USERS EXCEPT ADMIN
function removeUpdateNotifications(){
        	    add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
        	    add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
}


/*
@todo PRoveriti sta sve ovo znaci.
*/
function RemoveJunkFromHeader ()
{
    // remove junk from head
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
}

/*
 <?php if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<meta name="description" content="<?php the_excerpt_rss(); ?>" />
<?php endwhile; endif; elseif(is_home()) : ?>
<meta name="description" content="<?php bloginfo('description'); ?>" />
<?php endif; ?>
  
 * */

function mls_get_excerpt_rss() {
	$output = get_the_excerpt();
	return strip_shortcodes(apply_filters('the_excerpt_rss', $output));
}

/* Meta description of a site */
function dynamic_meta_description() {
    $rawcontent = null;
    
    if(is_single() || is_page()) { 
        if ( have_posts() ) { 
            while ( have_posts() ) { 
                the_post();
                $rawcontent = mls_get_excerpt_rss();//get_the_content(); 
            }
        }
    }
	
	if(empty($rawcontent)) {
		$rawcontent = htmlentities(bloginfo('description'));
	} else {
		$rawcontent = preg_replace('/\[.+\]/','', $rawcontent);
		$chars = array("", "\n", "\r", "chr(13)",  "\t", "\0", "\x0B");
		$rawcontent = htmlentities(str_replace($chars, " ", $rawcontent));
	}
	if (strlen($rawcontent) < 170) {
		return $rawcontent;
	} else {
		$desc = substr($rawcontent,0,170);
		return $desc;
	}
}

function csv_tags() {
    global $post;
    $csv_tags = '';
    if(isset($post) && is_object($post)){
        $posttags = get_the_tags($post->ID);
    	if(isset($posttags) && $posttags){
        	foreach((array)$posttags as $tag) {
        		$csv_tags .= $tag->name . ',';
        	}
        	$csv_tags = substr($csv_tags,0,-1);
        }
    }
	return $csv_tags;
}


/*
Automatically enable threaded comments
Threaded comments aren’t on by default. This can be fixed with the following.
*/
function enable_threaded_comments ()
{
    if (! is_admin()) {
        if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
            wp_enqueue_script('comment-reply');
    }
}
add_action('get_header', 'enable_threaded_comments');


function kids_comments($comment, $args, $depth){?>
<?php 
    $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
       <span id="comment-<?php comment_ID(); ?>">
            <p class="image">
                <span class="polaroid-mask"></span>
                <?php echo get_avatar( $comment->comment_author_email, $args['avatar_size'] ); ?>
            </p>
            <?php echo (($depth > 1) ? '<div class="comment-content">' : ''); ?> 
            
                    <p class="meta">
                        <span>Published by: <?php echo get_comment_author_link(); ?></span>
                        <span>Date: <a class="date" title="" href="#"><?php echo get_comment_date(); ?></a></span>
                    </p>   
                    <?php if ($comment->comment_approved == '0') : ?>
                         <em><?php _e('Your comment is awaiting moderation.') ?></em>
                         <br />
                    <?php endif; ?>
                    <?php edit_comment_link(__('(Edit)'),'  ','') ?>
                    
                    <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    <p> <?php comment_text() ?></p>
                    
            <?php echo (($depth > 1) ? '</div>' : ''); ?>     
      </span>      
<?php
}

function mls_custom_dynamic_sidebar($post_id, $theme_sidebar_id=null){
    $has_builtin = dynamic_sidebar($theme_sidebar_id);
    /* Get custom sidebars for this page/post */
    $data = get_post_meta($post_id, 'kids_sidebar_ids');
    
    if(!empty($data)){
        $data = $data[0];
    }
    $has_customs = false;
    foreach ($data as $sidebar_id){
        $has_customs = $has_customs || dynamic_sidebar('kids_' . $sidebar_id);
    }
    return $has_builtin || $has_customs;
}

/*
Customize the admin section footer
*/
function custom_admin_footer () 
{
    echo 'Aislin Themes.';
}
add_filter('admin_footer_text', 'custom_admin_footer');