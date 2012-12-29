<?php
/**
 *  Template Name: Contact Page Template 
 *  Description: A Page Template for Contact page
 *
 *  @package Aislin
 *  @subpackage Kids
 */
    get_header(); 
    get_template_part('template-part-intro-featured-ribbon'); ?>
    <div id="content">
        <div class="wrap">
            <div class="c-8">
                <div class="page">
                    <p><?php Data()->echoMain('mi_contact.intro_text') ?></p>
                    <?php  if(Data()->isOn('mi_contact.form_switch')) : ?>
                    <p class="contact-modal-box"><span></span></p>
                    <form enctype="multipart/form-data" method="post" id="contact-form"> 
                        <div class="send-form">   
                              <p>
                                  <label>*<?php _e('Your name', ThemeSetup::HOOK . '_theme'); ?>:</label>
                                  <input class="u-4" name="name" id="name" /><span></span>
                              </p>
                              <p>
                                  <label>*<?php _e('Your E-mail', ThemeSetup::HOOK . '_theme'); ?>:</label>
                                  <input class="u-4" name="email" id="email" /><span></span>
                              </p>
                              <p>
                                  <label><?php _e('Your Website', ThemeSetup::HOOK . '_theme'); ?>:</label>
                                  <input class="u-4" name="website" id="website" /><span></span>
                              </p>
                              <p>
                                  <label><?php _e('Subject', ThemeSetup::HOOK . '_theme'); ?>:</label>
                                  <input class="u-4" name="subject" id="subject" /><span></span>
                              </p>
                              <p>
                                  <label><?php _e('Your Message', ThemeSetup::HOOK . '_theme'); ?>:</label>
                                  <textarea class="u-6" name="message" id="message" cols="80" rows="5"></textarea><span></span>
                              </p>
                              <p>
                                  <input type="hidden" name="from_widget" value="0" />
                                  <a class="button-submit contact-button"><?php _e('Contact Us', ThemeSetup::HOOK . '_theme'); ?><span class="circle-arrow"></span></a>
                                  <a class="button-reset"><?php _e('Clear Form', ThemeSetup::HOOK . '_theme'); ?></a>
                              </p>
                              
                         </div><!--  end senf form -->   
                     </form>
                     <?php endif; ?>
                         <?php  if(Data()->isOn('mi_gmaps.mi_gmaps_switch')) : ?>
                         <div class="google-map">
                             <h3><?php _e('Find Us On Google Maps', ThemeSetup::HOOK . '_theme'); ?></h3>
                             <div class="google-map-background"></div>
                             <div id="google-map-location"></div>
                         </div><!--end google-map-->
                         <?php endif; ?>
                </div><!--  end page -->
            </div>
            <?php get_sidebar('contact'); // Call sidebar.php ?>
        </div><!-- end wrap -->
    </div><!-- end content -->
<?php get_footer(); ?>