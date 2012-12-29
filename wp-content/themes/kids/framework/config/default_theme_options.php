<?php
/*Default Configuration for Theme Options*/
 $meta_data = array('name'=>'main_settings', 'version'=>'1.4.3');

 /* Main Tabs */
 
/**
 * Option "content" represents name of a METHOD in ThemeAdmin Class OR file name where content is placed 
 */
$options['tabs'][] = array('name'=>__('Main Settings', 'kids_theme'), 	 'id'=>'main_settings_tab', 'type'=>'tab', 'content'=> KIDS_ADMIN_VIEW_DIR . '/main-settings.phtml');
$options['tabs'][] = array('name'=>__('Sliders', 'kids_theme'), 	     'id'=>'sliders_tab', 		'type'=>'tab', 'content'=> KIDS_ADMIN_VIEW_DIR . '/sliders.phtml');
$options['tabs'][] = array('name'=>__('Help', 'kids_theme'), 			 'id'=>'help_tab', 		    'type'=>'tab', 'content'=> KIDS_ADMIN_VIEW_DIR . '/help.phtml');

/** 
 * Menu Items in Main settings TAB 
 */
$options['menu_items'][] = array('name'=> __('Look & Feel', 'kids_theme'),   'id' => 'mi_look_feel', 		'type'=> 'menu_item', 'class'=>'icon-picture');
$options['menu_items'][] = array('name'=> __('Blog', 'kids_theme'), 		 'id' => 'mi_blog', 			'type'=> 'menu_item', 'class'=>'icon-pencil');
$options['menu_items'][] = array('name'=> __('Header', 'kids_theme'),  		  'id' => 'mi_header',         	'type'=> 'menu_item', 'class'=>'icon-arrow-up');
$options['menu_items'][] = array('name'=> __('Footer', 'kids_theme'),  		  'id' => 'mi_footer', 			'type'=> 'menu_item', 'class'=>'icon-arrow-down');
$options['menu_items'][] = array('name'=> __('Home Page', 'kids_theme'),  	  'id' => 'mi_home', 			'type'=> 'menu_item', 'class'=>'icon-home');
$options['menu_items'][] = array('name'=> __('Google Maps', 'kids_theme'),	  'id' => 'mi_gmaps', 			'type'=> 'menu_item', 'class'=>'icon-road');
$options['menu_items'][] = array('name'=> __('Contact', 'kids_theme'), 		  'id' => 'mi_contact',			'type'=> 'menu_item', 'class'=>'icon-book');
$options['menu_items'][] = array('name'=> __('Social', 'kids_theme'),  		  'id' => 'mi_social', 			'type'=> 'menu_item', 'class'=>'icon-user');
$options['menu_items'][] = array('name'=> __('Google Analitics', 'kids_theme'),'id' => 'mi_google_analitics', 'type'=> 'menu_item', 'class'=>'icon-map-marker');
$options['menu_items'][] = array('name'=> __('Sidebars', 'kids_theme'),'id' => 'mi_custom_sidebars', 'type'=> 'menu_item', 'class'=>'icon-list-alt');
$options['menu_items'][] = array('name'=> __('Misc', 'kids_theme'),    		   'id' => 'mi_misc', 			'type'=> 'menu_item', 'class'=>'icon-cog');


/* LOOK & FEEL */
$options['items']['mi_look_feel'][] = array('name'=>__('Colors & Images','kids_theme'), 
            							    'id'=>'look_feel_section', 
                             				'desc'=>__('common color settings', 'kids_theme'),
            							    'type'=>'start_section');

$options['items']['mi_look_feel'][] = array('name'=>__('Color Shemes','kids_theme'), 
							  'id'=>'look_feel_section', 
							  'type'=>'start_sub_section');

$options['items']['mi_look_feel'][] = array('id'=>'color_sheme',
                              'name'=>__('Color Shemes','kids_theme'), 
							  'desc'=>__('Select Color Sheme','kids_theme'),
                              'type'=>'select',
                              'required'=> true,
                              'default'=>'green-blue',
                              'values' => array('grey-red'		 => 'Grey and Red',
                              					'green-blue'     => 'Green and Blue',
                              					'orange-red'     => 'Orange and Red',
                              					'pink-grey'      => 'Pink and Grey',
                              					'purple-brown'   => 'Purple and Brown',
                              					'turquise-black' => 'Turquise and Black',
                              					'turquise-grey'  => 'Turquise and Grey',
                              					'turquise-orange'=> 'Turquise and Orange',
                              					'yellow-orange'  => 'Yellow and Orange',
                              					'yellow-purple'  => 'Yellow and Purple'),
                             'value' => null);
                             
 $options['items']['mi_look_feel'][] = array('name'=>__('Images','kids_theme'), 
							  'id'=>'img_section', 
							  'type'=>'start_sub_section');

 $options['items']['mi_look_feel'][] = array('id'=>'mi_logo',
                             'name'=>__('Logo', 'kids_theme'), 
		               		 'desc'=>__('Customize your logo. Logo height should not be greater than 100px.', 'kids_theme'),
                             'type'=>'file',
                             'default'=>KIDS_CSS_URI . '/color-shemes/green-blue/logo.png',
 							 'value' => null); 
 
 $options['items']['mi_look_feel'][] = array('id'=>'logo_width',
                             'name'=>__('Logo width','kids_theme'), 
							 'desc'=>__('Logo width in px. Leave this field blank for default value. Enter only a number.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'default'=> null); 
 
 $options['items']['mi_look_feel'][] = array('id'=>'logo_height',
                             'name'=>__('Logo Height','kids_theme'), 
							 'desc'=>__('Logo height in px. Leave this field blank for default value. Enter only a number.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'default'=>null); 
 
  $options['items']['mi_look_feel'][] = array('id'=>'mi_favicon',
                             'name'=>__('Favicon image','kids_theme'), 
							 'desc'=>__('Favicon image. 16x16 pixels.','kids_theme'),
                             'type'=>'file',
                             'value' => null,
                             'default'=>KIDS_IMAGES_URI . '/favicon.png');
  
  
 /* BLOG */
$options['items']['mi_blog'][] = array('name'=>__('Blog Settings','kids_theme'), 
							  'id'=>'blog_section', 
                			  'desc'=>__('all about blog', 'kids_theme'),
							  'type'=>'start_section');

$options['items']['mi_blog'][] = array('id'=>'mi_blog_meta_switch',
                             'name'=>__('Enable Meta Data','kids_theme'), 
							 'desc'=>__('Disable this option to hide blog post meta data.[date, author, categories]','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'on');

$options['items']['mi_blog'][] = array('id'=>'hide_cats',
                              'name'=>__('Hide Categories ','kids_theme'), 
							  'desc'=>__('Select categories you do not want to appear on your blog page. This option is related to a Kids Category widget.','kids_theme'),
                              'type'=>'multiselect',
                              'filters'=> array('intVal'),
                              'values' =>mls_get_categories(),
                              'value' => null,
 							  'default'=>0//array('empty')
                             );
  
 $options['items']['mi_blog'][] = array('id'=>'readmore',
                             'name'=>__('Read More Text Button','kids_theme'), 
							 'desc'=>__('Replace "Read More" with this text','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'default'=>__('Read More', 'kids_theme')); 
 
 $options['items']['mi_blog'][] = array('id'=>'comments_switch',
                             'name'=>__('Show comments','kids_theme'), 
							 'desc'=>__('Press Enabled button to allow users to make a comments','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'off'); 
 
 $options['items']['mi_blog'][] = array('id'=>'archive_sidebar_switch',
                             'name'=>__('Hide sidebar','kids_theme'), 
							 'desc'=>__('Enable this option in case you do not need a sidebar in Category/Archive pages.','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'off'); 

 
 /* SOCIAL PROFILES / ACCOUNTS */
$options['items']['mi_social'][] = array('name'=>__('Be Social','kids_theme'),
                             'id'=> 'social_section',
                             'desc'=>__('socialize it', 'kids_theme'),
                             'type'=>'start_section' );

$options['items']['mi_social'][] = array('id'=>'switch',
                             'name'=>__('Enable Social Icons','kids_theme'), 
							 'desc'=>__('Press disable to remove social icons in header section.','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'children'=>true,
                             'default'=>'on');

$options['items']['mi_social'][] = array('id'=>'title',
                             'name'=>__('Title','kids_theme'), 
							 'desc'=>__('Title will be shown near social icons. From the left.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>__('Enter Your Title','kids_theme'));   

$options['items']['mi_social'][] = array('id'=>'facebook',
                             'name'=>__('Facebook','kids_theme'), 
							 'desc'=>__('Link to your Facebook Profile/Page. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>'');

$options['items']['mi_social'][] = array('id'=>'twitter',
                             'name'=>__('Twitter','kids_theme'), 
							 'desc'=>__('Link to your Twitter profile. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>''); 

$options['items']['mi_social'][] = array('id'=>'googleplus',
                             'name'=>__('Google+','kids_theme'), 
							 'desc'=>__('Link to your Google+ profile. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>'');

$options['items']['mi_social'][] = array('id'=>'pinterest',
                             'name'=>__('Pin it','kids_theme'), 
							 'desc'=>__('Link to your Pinterest profile. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>'');  

$options['items']['mi_social'][] = array('id'=>'youtube',
                             'name'=>__('Youtube Channel','kids_theme'), 
							 'desc'=>__('Link to your Youtube profile. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>''); 

$options['items']['mi_social'][] = array('id'=>'linkedin',
                             'name'=>__('LinkedIn','kids_theme'), 
							 'desc'=>__('Link to your LinkedIn profile. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>'');

$options['items']['mi_social'][] = array('id'=>'rss',
                             'name'=>__('RSS Channel','kids_theme'), 
							 'desc'=>__('Full URL to your newsfeeds. (Do not forget do add "http://" as prefix)','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id'=>'switch',
                             'depend_id'=>'on',
                             'default'=>'');  

/* CONTACT INFO */
$options['items']['mi_contact'][] = array('name'=>__('Contact Info / Settings','kids_theme'), 
        							  'id'=>'contact_section', 
                        				'desc'=>__('contact info', 'kids_theme'),
        							  'type'=>'start_section');

//@todo Wordpress Settings Email
$options['items']['mi_contact'][] = array('id'=>'email',
                             'name'=>__('E-mail Address','kids_theme'), 
							 'desc'=>__('E-mail address displayed at contact page.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'required' => true,
                             'filters' => array('strip_tags'),
                             'validators' => array('_email'),
                             'default'=>'contact@example.com');

$options['items']['mi_contact'][] = array('id'=>'tel',
                             'name'=>__('Phone','kids_theme'), 
							 'desc'=>__('Phone number diplayed at Contact page.','kids_theme'),
                             'type'=>'text',
                             'validators'=>array('_phone'),
                             'value' => null,
                             'default'=>'+381 63 1234 567');

$options['items']['mi_contact'][] = array('id'=>'fax',
                             'name'=>__('Fax','kids_theme'), 
							 'desc'=>__('Fax number diplayed at Contact page.','kids_theme'),
                             'type'=>'text',
							 'filters'=>array('strip_tags'),
                             'validators'=>array('_phone'),
                             'value'=> null,
                             'default'=>'+381 63 1234 567');

$options['items']['mi_contact'][] = array('id'=>'address',
                             'name'=>__('Full Address','kids_theme'), 
				             'desc'=>__('Address displayed at Contact page.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'default'=>'900 John Street New York, NY 21000');

$options['items']['mi_contact'][] = array('name'=>__('Contact Form','kids_theme'), 
                          							  'id'=>'mi_contact_sub_section', 
                          							  'type'=>'start_sub_section');

$options['items']['mi_contact'][] = array('id'=>'form_switch',
                             'name'=>__('Show Contact Form','kids_theme'), 
				             'desc'=>__('To hide contact form disable this option', 'kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'on');

$options['items']['mi_contact'][] = array('id'=>'switch_auto_reply',
                             'name'=>__('Auto Reply','kids_theme'), 
							               'desc'=>__('If you enable this option, visitors who send you an email message will receive a confirmation e-mail message.','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'off');

$options['items']['mi_contact'][] = array('id'=>'email_subject',
                             'name'=>__('E-mail Subject','kids_theme'), 
							               'desc'=>__('Enter an e-mail subject of automated e-mail message.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'default'=>'');

$options['items']['mi_contact'][] = array('id'=>'email_sender_name',
                             'name'=>__('Your name','kids_theme'), 
							 'desc'=>__('"From" field in an automated e-mail message.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'default'=>'');


//@todo Wordpress Settings Email
$options['items']['mi_contact'][] = array('id'=>'email_contact_form',
                             'name'=>__('E-mail address','kids_theme'), 
							 'desc'=>__('Email address where you will receiving messages from contact form. This e-mail address will be included in a "From" field of an Automated e-mail message','kids_theme'),
                             'type'=>'text',
                             'validators'=>array('_email'),
                             'value' => null,
                             'default'=>'test@example.com');

$options['items']['mi_contact'][] = array('id'=>'intro_text',
                              'name'=>__('Contact message','kids_theme'), 
							  'desc'=>__('This text is shown above a contact form.','kids_theme'),
                              'type'=>'textarea',
                              'value' => null,
                              'default'=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vel fringilla neque. Suspendisse fermentum mi ante, eget posuere purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam ac felis justo. Proin pharetra tincidunt risus bibendum condimentum. Praesent vestibulum commodo diam ac mattis. Mauris aliquet faucibus metus, nec fermentum orci porttitor sit amet. Quisque fringilla aliquet suscipit. Phasellus et tellus eu quam posuere sagittis. Nunc ut aliquam dui. Ut purus orci, placerat at lobortis vitae, interdum a nunc. Donec elementum nisl id arcu bibendum eu tincidunt nibh consectetur."
                             );

$options['items']['mi_contact'][] = array('id'=>'auto_msg',
                              'name'=>__('Response Message','kids_theme'), 
							  'desc'=>__('Text of an Autoreply e-mail message.','kids_theme'),
                              'type'=>'textarea',
                              'value' => null,
                              'default'=>"Dear Sir or Madam,\n\nWe have received your message. This is an automated reply e-mail message. Please do not reply."
                             );
                             

/* GOOLGE ANALITICS */
$options['items']['mi_google_analitics'][] = array('name'=>__('Google Analitics','kids_theme'), 
                    							  'id'=>'google_analitics_section', 
                                    			  'desc'=>__('google', 'kids_theme'),
                    							  'type'=>'start_section');

$options['items']['mi_google_analitics'][] = array('id'=>'google_analitics_switch',
                              'name'=>__('Enable Google Analitics','kids_theme'), 
							  'desc'=>__('By default Google Analitics are disabled. Click Enabled to activate it.','kids_theme'),
                              'type'=>'checkbox',
                              'value' => null,
                              'children'=>true,
                              'default'=>'off'
                             );

$options['items']['mi_google_analitics'][] = array('id'=>'google_analitics',
                              'name'=>__('Google Analitics','kids_theme'), 
							  'desc'=>__('If you have a google analytics account setup, you can paste your code in the text area to the side. Make sure to include the opening and closing script tags. Your tracking code will be copied to the footer of each page on your site.
							  ','kids_theme'),
                              'type'=>'textarea',
                              'value' => null,
                              'parent_id'=>'google_analitics_switch',
                              'depend_id'=>'on',
                              'default'=>''
                             );


/* HEADER */
$options['items']['mi_header'][] = array('name'=>__('Header settings','kids_theme'), 
    							'id'=>'header_section',
                                'desc'=>__('setup your header', 'kids_theme'), 
    							'type'=>'start_section');  


$options['items']['mi_header'][] = array('id'=>'teaser_switch',
                             'name'=>__('Teaser text','kids_theme'), 
							 'desc'=>__('Disable this option to hide Teaser','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'on');

$options['items']['mi_header'][] = array('id'=>'breadcrumbs_switch',
                             'name'=>__('Breadcrumbs','kids_theme'), 
							 'desc'=>__('Disable this option to hide breadcrumbs','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'on');

$options['items']['mi_header'][] = array('id'=>'bread_crumbs_text_switch',
                             'name'=>__('Hide breadcrumbs text','kids_theme'), 
				             'desc'=>__('Enable this option to hide text "You are here:"','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'on');

 /* FOOTER */                
$options['items']['mi_footer'][] = array('name'=>__('Footer settings','kids_theme'), 
                        							'id'=>'footer_section', 
                                      				'desc'=>__('setup your footer', 'kids_theme'),
                        							'type'=>'start_section');  


$options['items']['mi_footer'][] = array('id'=>'mi_copyright_switch',
                                         'name'=>__('Copyright Text','kids_theme'), 
            			                 'desc'=>__('Show or Hide Copyright in footer section. Thick checkbox to show.','kids_theme'),
                                         'type'=>'checkbox',
                                         'children'=>true,
                                         'value' => null,
                                         'default'=>'on');

$options['items']['mi_footer'][] = array('id'=>'mi_copyright',
                                         'name'=>__('Copyright','kids_theme'), 
            			                 'desc'=>__('Copyright text in footer section','kids_theme'),
                                         'type'=>'text',
                                         'parent_id'=>'mi_copyright_switch',
                                         'depend_id'=>'on',
                                         'value' => null,
                                         'default'=>'Copyright &copy; 2011-2012 Your Company Name. All Rights Reserved.');


$options['items']['mi_footer'][] = array('id'=>'mi_terms_switch',
                                         'name'=>__('Show Terms of Use Link','kids_theme'), 
            			                 'desc'=>'',
                                         'type'=>'checkbox',
 										 'children'=>true,
                                         'value' => null,
                                         'default'=>'on');

$options['items']['mi_footer'][] = array('id'=>'mi_terms',
                                         'name'=>__('Terms of Use Link','kids_theme'), 
            							 'desc'=>__('Link to the page "Term of Use"','kids_theme'),
                                         'type'=>'text',
                                         'parent_id'=>'mi_terms_switch',
                                         'depend_id'=>'on',
                                         'value' => null,
                                         'default'=>'');

$options['items']['mi_footer'][] = array('id'=>'mi_copyright_link_switch',
                                         'name'=>__('Show Copyright Link','kids_theme'), 
            							 'desc'=> '',
                                         'type'=>'checkbox',
                                         'children'=>true,
                                         'value' => null,
                                         'default'=>'on');

$options['items']['mi_footer'][] = array('id'=>'mi_copyright_link',
                                         'name'=>__('Copyright Link','kids_theme'), 
            							 'desc'=>__('Link to the Copyrights','kids_theme'),
                                         'type'=>'text',
                                         'parent_id'=>'mi_copyright_link_switch',
                                         'depend_id'=>'on',
                                         'value' => null,
                                         'default'=>'');

$options['items']['mi_footer'][] = array('id'=>'mi_sitemap_switch',
                                         'name'=>__('Show Sitemap Link','kids_theme'), 
            							 'desc'=>'',
                                         'type'=>'checkbox',
                                         'children'=>true,
                                         'value' => null,
                                         'default'=>'off');

$options['items']['mi_footer'][] = array('id'=>'mi_sitemap_link',
                                         'name'=>__('Sitemap Link','kids_theme'), 
            							 'desc'=>__('Full Url to a Sitemap page','kids_theme'),
                                         'type'=>'text',
                                         'parent_id'=>'mi_sitemap_switch',
                                         'depend_id'=>'on',
                                         'value' => null,
                                         'default'=>'');


/* HOME PAGE */
$options['items']['mi_home'][] = array( 'name'=>__('Home Page settings','kids_theme'), 
            							'id'=>'home_section', 
                                        'desc'=>'',
            							'type'=>'start_section');  

$options['items']['mi_home'][] = array('id'=>'slider_hide_switch',
                             'name'=>__('Hide Slider','kids_theme'), 
                             'desc'=>__('Enable this option to hide the Slider section at home page.','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'default'=>'off');

$options['items']['mi_home'][] = array('name'=>__('Scroller Settings','kids_theme'), 
							  'id'=>'mi_home_sub_section', 
							  'type'=>'start_sub_section'
                              );

$options['items']['mi_home'][] = array('id'=>'scroller_switch',
                             'name'=>__('Show vertical scroller','kids_theme'), 
                             'desc'=>__('Enable this option to show a scroller at home page.','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'children'=>true,
                             'default'=>'off');

$options['items']['mi_home'][] = array('id'=>'scroller_items_num',
                             'name'=>__('Number of posts','kids_theme'), 
							 'desc'=>__('Number of items per slide.','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'filters'=> array('intVal'),
                             'parent_id' => 'scroller_switch',
                             'depend_id' =>'on',
                             'default'=> 3);

$options['items']['mi_home'][] = array('id'=>'scroller_title',
                             'name'=>__('Scroller Title','kids_theme'), 
							 'desc'=>__('Scroller Title at Home Page','kids_theme'),
                             'type'=>'text',
                             'value' => null,
                             'parent_id' => 'scroller_switch',
                             'depend_id' =>'on',
                             'default'=>__('OUR PROGRAM', 'kids_theme'));

$options['items']['mi_home'][] = array('id'=>'scroller_resource_switch',
                              'name'=>__('Scroller data source','kids_theme'), 
							  'desc'=>__('Select a data resource for a vertical scroller at home page','kids_theme'),
                              'type'=>'radio',
                              'default'=>'spost',
							  'parent_id' => 'scroller_switch',
                              'children' => true,
                              'depend_id' =>'on',
                              'values' =>array('spost'=>__('Posts','kids_theme'), 'spage'=>__('Pages','kids_theme')),
                              'value' => null
                             );

$options['items']['mi_home'][] = array('id'=>'scroller_category',
                              'name'=>__('Category','kids_theme'), 
							  'desc'=>__('Select a category for a vertical scroller at home page','kids_theme'),
                              'type'=>'select',
                              'depend_id'=>'spost',
                              'parent_id' => 'scroller_resource_switch',
                              'default'=> array(),
                              'values' =>mls_get_categories(),
                              'value' => null
                             );
                             
$options['items']['mi_home'][] = array('id'=>'scroller_pages',
                              'name'=>__('Pages','kids_theme'), 
							  'desc'=>__('Select a category for a vertical scroller at home page','kids_theme'),
                              'type'=>'multiselect',
 							  'depend_id'=>'spage',
                              'parent_id' => 'scroller_resource_switch',
                              'default'=> array(),
                              'values' =>mls_get_pages(),
                              'value' => null
                             );

$options['items']['mi_home'][] = array('name'=>__('Featured section','kids_theme'), 
                                                    'id'=>'mi_home_featured_sub_section', 
                                                    'type'=>'start_sub_section'
                                                    );

$options['items']['mi_home'][] = array('id'=>'featured_section_switch',
                                     'name'=>__('Show Featured section','kids_theme'), 
        				             'desc'=>__('To hide featured section disable this option', 'kids_theme'),
                                     'type'=>'checkbox',
                                     'children'=>true,
                                     'value' => null,
                                     'default'=>'on');

$options['items']['mi_home'][] = array('id'=>'featured_section_source_switch',
                                     'name'=>__('Data Source for Featured section','kids_theme'), 
        				             'desc'=>__('Choose between posts or pages.', 'kids_theme'),
                                     'type'=>'radio',
                                     'children'=>true,
                                     'parent_id' => 'featured_section_switch',
                                     'depend_id' =>'on',
                                     'values' => array('post'=>__('Posts'), 'page'=>__('Pages', 'kids_theme')),
                                     'value' => null,
                                     'default'=>'post');

$options['items']['mi_home'][] = array('id'=>'featured_section_post',
                                       'name'=>__('Featured Post','kids_theme'), 
        				               'desc'=>__('Select a post shown at featured area.', 'kids_theme'),
                                       'type'=>'select',
                                       'parent_id' => 'featured_section_source_switch',
									   'depend_id'=>'post',
                                       'value' => null,
                                       'default'=>null,
                                       'values'=> mls_get_posts());

$options['items']['mi_home'][] = array('id'=>'featured_section_page',
                                       'name'=>__('Featured Page','kids_theme'), 
        				               'desc'=>__('Select a page shown at featured area.', 'kids_theme'),
                                       'type'=>'select',
                                       'depend_id'=>'page',
                                       'parent_id'=>'featured_section_source_switch',
                                       'value' => null,
                                       'default'=>null,
                                       'values'=> mls_get_pages(array('sort_column'=>'post_title')));

$options['items']['mi_home'][] = array('id'=>'featured_section_full',
                                     'name'=>__('Show entire post/page content','kids_theme'), 
        				             'desc'=>__('By default it shows an excerpt without formatting. Change this option to show entire content of a post/page.', 'kids_theme'),
                                     'type'=>'checkbox',
                                     'parent_id'=>'featured_section_switch',
                                     'depend_id'=>'on',
                                     'value' => null,
                                     'default'=>'off');

$options['items']['mi_home'][] = array('id'=>'featured_section_readmore_switch',
                                     'name'=>__('Show Read more link','kids_theme'), 
        				             'desc'=>__('To hide Read more link in featured section disable this option', 'kids_theme'),
                                     'type'=>'checkbox',
                                     'parent_id'=>'featured_section_switch',
                                     'depend_id'=>'on',
                                     'value' => null,
                                     'default'=>'on');

$options['items']['mi_home'][] = array('id'=>'featured_section_widget_switch',
                                     'name'=>__('Show Widget','kids_theme'), 
        				             'desc'=>__('To hide widget area in featured section disable this option', 'kids_theme'),
                                     'type'=>'checkbox',
                                     'parent_id'=>'featured_section_switch',
                                     'depend_id'=>'on',
                                     'value' => null,
                                     'default'=>'on');

$options['items']['mi_home'][] = array('name'=>__('Middle section (3 posts)','kids_theme'), 
            							  'id'=>'home_section', 
            							  'type'=>'start_sub_section');

$options['items']['mi_home'][] = array('id' =>'middle_switch',
                                     'name' =>__('Show Middle Page area','kids_theme'), 
        				             'desc' =>__('To hide middle section disable this option', 'kids_theme'),
                                     'type' =>'checkbox',
                                     'children' => true,
                                     'value' => null,
                                     'default' =>'on');

$options['items']['mi_home'][] = array('id'=>'content_section_source_switch',
                                     'name'=>__('Data Source for Middle section','kids_theme'), 
        				             'desc'=>__('Choose between posts or pages.', 'kids_theme'),
                                     'type'=>'radio',
                                     'children'=>true,
                                     'parent_id' => 'middle_switch',
                                     'depend_id' =>'on',
                                     'values' => array('mpost'=>__('Posts'), 'mpage'=>__('Pages', 'kids_theme')),
                                     'value' => null,
                                     'default'=>'mpost');

$options['items']['mi_home'][] = array('id'=>'content_section_category',
                                       'name'=>__('Post list','kids_theme'), 
        				               'desc'=>__('Posts from this category will be shown at home page.', 'kids_theme'),
                                       'type'=>'select',
                                       'value' => null,
                                       'default'=>null,
                                       'parent_id' => 'content_section_source_switch',
                                       'depend_id' => 'mpost',
                                       'values'=> mls_get_categories(array( 'type'    => 'post',
                                                                            'orderby' => 'name',
                                                                            'order'   => 'ASC',
                                                                            'hide_empty' => 1,
                                                                            'taxonomy'   => 'category'))
                                       );
                                       
$options['items']['mi_home'][] = array('id'        => 'content_section_pages',
                                       'name'      => __('Pages','kids_theme'), 
        				               'desc'      => __('Select 3 pages for middle page section.', 'kids_theme'),
                                       'type'      => 'multiselect',
                                       'value'     => null,
                                       'default'   => array(),
                                       'parent_id' => 'content_section_source_switch', 
                                       'depend_id' => 'mpage',
                                       'values'    => mls_get_pages(array('sort_column'=>'post_title'))
                                       );

                             
/* GOOGLE MAPS */
$options['items']['mi_gmaps'][] = array('name'=>__('Google Maps Settings','kids_theme'), 
    							'id'=>'gmaps_section', 
                  				'desc'=>__('google maps', 'kids_theme'),
    							'type'=>'start_section');  

$options['items']['mi_gmaps'][] = array('id'=>'mi_gmaps_switch',
                             'name'=>__('Google Maps','kids_theme'), 
			                 'desc'=>__('Turn this option on if you want to enable google maps.','kids_theme'),
                             'type'=>'checkbox',
                             'children'=>true,
                             'value' => null,
                             'default'=>'off');

$options['items']['mi_gmaps'][] = array('id'=>'mi_gmaps_zoom',
                             'name'=>__('Google Maps Zoom','kids_theme'), 
							 'desc'=>__('Zoom parameter. Valid values are from 1 to 20.','kids_theme'),
                             'type'=>'text',
                             'parent_id'=>'mi_gmaps_switch',
                             'depend_id'=>'on',
                             'value' => null,
                             'default'=>'16');

$options['items']['mi_gmaps'][] = array('id'=>'mi_gmaps_lat',
                             'name'=>__('Latitude','kids_theme'), 
				             'desc'=>__('Enter first coordinate.','kids_theme'),
                             'type'=>'text',
                             'parent_id'=>'mi_gmaps_switch',
                             'depend_id'=>'on',
                             'value' => null,
                             'default'=>'');

$options['items']['mi_gmaps'][] = array('id'=>'mi_gmaps_long',
                             'name'=>__('Longitude','kids_theme'), 
			                 'desc'=>__('Enter second coordinate.','kids_theme'),
                             'type'=>'text',
                             'parent_id'=>'mi_gmaps_switch',
                             'depend_id'=>'on',
                             'value' => null,
                             'default'=>'');

/* CUSTOM SIDEBARS */
$options['items']['mi_custom_sidebars'][] = array('name'=>__('Custom Sidebars','kids_theme'), 
    							'id'=>'csidebars_section', 
                  				'desc'=>__('Create your sidebars', 'kids_theme'),
    							'type'=>'start_section');  
                             
$options['items']['mi_custom_sidebars'][] = array('id' => 'csidebar_list',
                               'name'      => __('Sidebar Name', 'kids_theme'), 
				               'desc'      => __('Use letters and numbers allowed.', 'kids_theme'),
                               'type'      => 'list',
                               'value'     => null,
                               'default'   => null,
                               'values'    => array()
                               );
                     

/* MISC */
$options['items']['mi_misc'][] = array('name'=>__('Misc settings','kids_theme'), 
    							'id'=>'misc_section', 
                  				'desc'=>__(' ', 'kids_theme'),
    							'type'=>'start_section');  

$options['items']['mi_misc'][] = array('name'=>__('While updating website','kids_theme'), 
							  'id'=>'mi_updating_section1', 
							  'type'=>'start_sub_section'
                              ); 


$options['items']['mi_misc'][] = array('id'=>'mi_updating_in_progress',
                             'name'=>__('Updating site','kids_theme'), 
			                 'desc'=>__('Turn this option "on" while updating your website to redirect all visitors to under construction page. Administrators are NOT redirected.','kids_theme'),
                             'type'=>'checkbox',
                             'value' => null,
                             'children'=>true,
                             'default'=>'off');

$options['items']['mi_misc'][] = array('id'=>'mi_updating_teaser_text',
                             'name'=>__('Teaser text','kids_theme'), 
			                 'desc'=>__('Text positioned in teaser section.','kids_theme'),
                             'type'=>'text',
 							 'parent_id'=>'mi_updating_in_progress',
                             'depend_id'=>'on',
                             'value' => null,
                             'default'=>'Updating...');

$options['items']['mi_misc'][] = array('id'=>'mi_updating_title',
                             'name'=>__('Updating title','kids_theme'), 
			                 'desc'=>__('Title in the middle of the page.','kids_theme'),
                             'type'=>'text',
 							 'parent_id'=>'mi_updating_in_progress',
                             'depend_id'=>'on',
                             'value' => null,
                             'default'=>'Updating in progress');

$options['items']['mi_misc'][] = array('id'=>'mi_updating_text',
                             'name'=>__('Updating text','kids_theme'), 
			                 'desc'=>__('Text in the middle of the page.','kids_theme'),
                             'type'=>'textarea',
                             'parent_id'=>'mi_updating_in_progress',
                             'depend_id'=>'on',
                             'value' => null,
                             'default'=>'We\'re coming back soon'); 

$options['items']['mi_misc'][] = array('name'=>__('Customs','kids_theme'), 
							  'id'=>'mi_custom_section', 
							  'type'=>'start_sub_section'
                              );
                              
$options['items']['mi_misc'][] = array('id'=>'mi_custom_css',
                              'name'=>__('Custom CSS','kids_theme'), 
			                  'desc'=>__('Enter Your custom CSS code.','kids_theme'),
                              'type'=>'textarea',
                              'value' => null,
                              'default'=>''
                             );
                             
$options['items']['mi_misc'][] = array('id'=>'mi_custom_js',
                              'name'=>__('Custom Javascript','kids_theme'), 
			                  'desc'=>__('Enter Your custom JS code.','kids_theme'),
                              'type'=>'textarea',
                              'value' => null,
                              'default'=>''
                             );  
                             
$options['items']['mi_misc'][] = array('id'=>'mi_404',
                              'name'=>__('404 text','kids_theme'), 
			                  'desc'=>__('This message is shown when requested page can not be found','kids_theme'),
                              'type'=>'textarea',
                              'value' => null,
                              'default'=>'Ooops. Something went wrong. Requested page does not exists. Make sure you entered correct URL.'
                             );  
                             
                             