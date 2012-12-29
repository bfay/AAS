<?php
require 'class.phpmailer-lite.php';

class ThemeMailer extends PHPMailerLite
{
    /**
     * Fields Map
     * @var unknown_type
     */
    protected $fields = array();
    /* Submited data */
    
    protected $data = array();
    /**
     * List of Errors
     * Enter description here ...
     * @var unknown_type
     */
    
    public $errors = array();
    /**
     * Request sent by Ajax request
     * @var unknown_type
     */
    
    /**
     * JSON response
     * @var unknown_type
     */
    public $response = null;
    
    /**
		Output flag
     */
    protected $echo = false;
    
    
    protected $rawData = array();
    
    
    protected $ajax_flag = false;
    public function __construct ($fields = array())
    {
        parent::__construct();
        if (empty($fields)) {
            throw new Exception('List of fields is required.', '1000');
        }
        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
         strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->ajax_flag = true; 
        }
        $this->fields = $fields;
        
        $this->rawData = $_POST['form_data'];
    }
    
    public function sanitarize ()
    {
        foreach ($this->fields as $f_name => $value) {
            if(isset($this->rawData[$f_name])){
             $this->data[$f_name] = strip_tags( $this->rawData[$f_name]);
             $this->data[$f_name] = trim( $this->data[$f_name]);
            }
            else{
                $this->data[$f_name] = ''; 
            }
        }
    }
    
    public function validate ()
    {
        foreach ($this->fields as $f_name => $value) {
            $validators = explode('|', $value);
            
            if (isset($validators[0]) && $validators[0] == '*' &&  $this->data[$f_name] == '') { // Required
                $this->errors[$f_name] = 'Required';
            } else {
                if( $this->data[$f_name] != ''){
                    if (isset($validators[1]) && method_exists($this, $validators[1])) {
                        $valid = $this->{$validators[1]}( $this->data[$f_name]);
                        if (! $valid) {
                            $this->errors[$f_name] = 'Invalid Data';
                        }
                    }
                }
            }
        }
        return !$this->hasErrors();
    }
    public function doMagic ()
    {
        $this->sanitarize();
        if ($this->validate()) { // success
            //Get Data from DB
            $wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
            $opts = get_option('kids_options');
            
            if(!empty($opts)){
                
                 /* Data for Auto response */
                 $sender      = $receiver = $opts['main_settings']['mi_contact']['email_contact_form'];
                 $sender_name = $opts['main_settings']['mi_contact']['email_sender_name'];
                 $auto_response_flag = $opts['main_settings']['mi_contact']['switch_auto_reply'];
                 
                 //Send mail to a site owner
                 try {
                          /* Message for owner */
                         $mail_body = $this->buildMsgBody();
                         $this->IsMail();
                         $this->IsHTML(true);
                         $this->CharSet = 'utf-8';
                         $this->SetFrom($sender, $sender_name);
                         $this->AddAddress($sender, 'Contact Form');
                         $this->Subject = 'Message sent via contact form.';
                         $this->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                         $this->Body = $this->buildMsgBody();
                         
                         if(!$this->Send()){
                            $this->errors['msg'] = __('Unable to send an e-mail message. Please contact an administrator.', ThemeSetup::HOOK . '_theme');                         
                         }
                         
                 } catch (phpmailerException $e) {
                    $this->errors['msg'] = $e->errorMessage();
                 } catch (Exception $e) {
                    $this->errors['msg'] = $e->errorMessage();
                 }
                 $this->ClearAddresses();
                 
                 if(!$this->hasErrors() && $auto_response_flag == 'on'){
                     
                     $subject            = $opts['main_settings']['mi_contact']['email_subject']; //For user who filled in a contact form
                     $auto_response      = $opts['main_settings']['mi_contact']['auto_msg'];
                     
                     /* Message to visitor */
                    try {
                        $this->IsMail();
                        $this->IsHTML(false);
                        $this->CharSet = 'utf-8';
                        $this->SetFrom($sender, $sender_name);
                        $this->AddAddress($this->data['email'], $this->data['name']);
                        $this->Subject = $subject;
                        $this->AltBody = $auto_response;//o view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                        $this->Body = $auto_response;
                        if(!$this->Send()){
                            $this->errors['msg'] = __('Unable to send an e-mail message. Please contact an administrator.', ThemeSetup::HOOK . '_theme');                         
                         }
                    } catch (phpmailerException $e) {
                        $this->errors['msg'] = $e->errorMessage();
                    } catch (Exception $e) {
                        $this->errors['msg'] = $e->errorMessage();
                    }
                 }
            } else{
                $this->errors['msg'] = __('Unable to Sent an email message. Please try again.', ThemeSetup::HOOK.'_theme');
            }
        } else{ 
            $this->errors['msg'] = __('Fields marked with an Asterisk (*) are required.', ThemeSetup::HOOK.'_theme');
        }
        
        if($this->hasErrors()){
            $response = array('status' => 'error', 'data' => $this->errors);
        } else{
             $response = array('status' => 'success', 'data'=> array('msg' => __('Mail Sent. Thank You!', ThemeSetup::HOOK.'_theme')));
        }
        
        $this->response = $response;
        
        if ($this->ajax_flag === true && $this->echo) {
            die(json_encode($response));
        } else {
            //Do something smart!
        }
    }
    
    public function buildMsgBody(){
        
        $body = '<p>Mail sent via contact form.</p>';
        $body .= '<ul>';
        $body .= '<li>Name: '.$this->data['name'].'</li>';
        $body .= '<li>Subject: '.$this->data['subject'].'</li>';
        $body .= '<li>Mail: '.$this->data['email'].'</li>';
        $body .= '<li>Web Address: '.$this->data['website'].'</li>';
        $body .= '<li>Message: '.$this->data['message'].'</li>';
        $body .= '</ul>';
        
        return $body;
    }
    
    public function hasErrors ()
    {
        return !empty($this->errors);
    }
    protected function v_plain ($txt)
    {
        return true;
    }
    protected function v_text($subject)
    {
        return preg_match("/[ a-zA-Z0-9\.,\-'\\\"]/u", $subject);
    }
    protected function v_mail($address)
    {
        if (function_exists('filter_var')) { //Introduced in PHP 5.2
            if (filter_var($address, FILTER_VALIDATE_EMAIL) == false) {
                return false;
            } else {
                return true;
            }
        } else {
            return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', 
            $address);
        }
    }
    protected function v_url($url)
    { //Introduced in PHP 5.2
        /* if (function_exists('filter_var')) { //Introduced in PHP 5.2
            if (filter_var($url, FILTER_VALIDATE_URL) == false) {
                return false;
            } else {
                return true;
            }
         }else{*/
             $regex = "/^((http|https):\/\/){0,1}([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";   
             return preg_match($regex, $url);
        // }
    }
    
    public function getJsonResponse(){
        return $this->response;
    }
}