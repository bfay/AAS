<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) { ?>
		This post is password protected. Enter the password to view comments.
	<?php
		return;
	}
?>

<?php if ( have_comments() ) : ?>
	
    <div id="comments">
        <h2><?php comments_number('0', '1', '%'); ?> Comments: "<?php the_title(); ?>"</h2>
    	<div class="navigation">
    		<div class="next-posts"><?php previous_comments_link() ?></div>
    		<div class="prev-posts"><?php next_comments_link() ?></div>
    	</div>
    
        <?php $args = array(
                            'walker'            => null,
                            'style'             => 'ol',
                            'callback'          => 'kids_comments',
                            'type'              => 'all',
                            'avatar_size'       => 72,
                            'reverse_top_level' => null ); 
        ?>
    	<ol>
    		<?php wp_list_comments($args); ?>
    	</ol>
    
    	<div class="navigation">
    		<div class="next-posts"><?php previous_comments_link() ?></div>
    		<div class="prev-posts"><?php next_comments_link() ?></div>
    	</div>
    </div> </div> <!-- #comments -->   
	
<?php else : // this is displayed if there are no comments so far ?>
	 <?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
	 <?php else : // comments are closed ?>
		<p>Comments are closed.</p>
	 <?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div id="respond" class="add-comment">

<!-- <h2><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h2>  -->   
	<div class="cancel-comment-reply">
		<?php cancel_comment_reply_link(); ?>
	</div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>

    	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        
    		<?php if ( is_user_logged_in() ) : ?>
    			<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
    		    <p class="textarea">
                    <label for="message" class="add-comment-heading">Leave a comment:</label>
                    <textarea name="comment" id="comment" rows="3" tabindex="4"></textarea>
                </p>
    		<?php else : ?>
                <p class="textarea">
                    <label for="message" class="add-comment-heading">Leave a comment:</label>
                    <textarea name="comment" id="comment" rows="3" class="u-4" tabindex="4"></textarea>
                </p>
                <p>
                    <label for="name"><?php if ($req) echo "(*) "; ?>Your name:</label>
                    <input type="text" class="u-3" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                </p>
                <p>
                    <label for="email"><?php if ($req) echo "(*) "; ?>Your email (will not be published):</label>
                    <input type="text" class="u-3" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>/>
                    </p>  
                <p>
                    <label for="website">Your website:</label>
                    <input type="text" class="u-3" name="website" id="website" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="3" />
                </p>
                
    		<?php endif; ?>
             <p>
                <input type="submit" style="display: none;" name="submitx" class="submit" value="Send Comment" tabindex="5">
                <a class="button-submit comment-submit-button">Send Comment<span class="circle-arrow"></span></a>
                <?php comment_id_fields(); ?>
            </p>
    		<?php do_action('comment_form', $post->ID); ?>
    	</form>
        
	<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; ?>