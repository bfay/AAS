=== Easy post subtitle ===
Contributors: GreatWPPlugins
Donate link: http://www.greatwpplugins.com/
Tags: post, title, subtitle
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.0

== Description ==

Easy post subtitle is a simple Wordpress plugin that adds an input field to the post edit section, similar tot the title field, so that you can add to the post, beside the classic title, a subtitle which
can then be shown on the public side anywhere in the post (before the title, after it, etc. ).

Requires WordPress 3.0 and PHP 5. 

**Usage**

* Enter WP admin,
* Edit or add a post,
* Fill the "Subtitle" field with the desired content,
* Press Publish,

Now the post has a subtitle. For displaying it on the public side you just have to insert the following code in the post file ( from the used template ): `<?php echo the_subtitle(); ?>`.
Note that this code has to be inserted only once in the desired place, and then you can add different post's subtitle as you desire without other intervention in the code.

If you have suggestions or questions about this plugin, feel free to email me at contact@greatwpplugins.com or visit our page www.greatwpplugins.com.

== Installation ==

1. Upload the folder 'easy-post-subtitle' to the `/wp-content/plugins/` directory
2. Activate the "Easy post subtitle" plugin through the 'Plugins' menu in WordPress
3. You are now ready to add subtitles to your posts.

== Frequently Asked Questions ==

= Do I need special skills to use this plugin? =

Installing the plugin is easily done in 2 steps described in the Installation section. 

To make the subtitle visible on the public side you just need to know which is the template's post file to add the code `<?php echo the_subtitle(); ?>`

Simple, isn't it?


== Screenshots ==

1. Editing a post and adding the subtitle.
2. The final result on the edited post

== Changelog ==

= 1.0 =
* Initial release