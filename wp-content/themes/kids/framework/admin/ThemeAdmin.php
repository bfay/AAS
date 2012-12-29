<?php
/**
 *  Managing theme administration panel
 */

if(!defined('RUN_FOREST_RUN')) exit('No Direct Access');
/**
*  Main Administration class
*  Some sort of controller
*/

class ThemeAdmin {

	/* Self */
	private static $_instance = null;
	
	/* Instance of ThemeOptions Class */
	protected $data_obj = null;
	
	/* Flag */
	protected $is_initialized = false;
	
	/* All nofication in admin panel */
	protected $notifications = array();
	
	protected $nonce = '';
	
	private function __construct(){}
	private function __clone(){}

	public static function getInstance(){
		if (null === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

/**
 *  @todo If there is NO $data_obj load IT!
 */
	public function init(){
	    if(!$this->is_initialized){
    		$obj = self::getInstance();
    		/* Ovde treba instancirati ThemeOptions klasu a ne ThemeSetup */
    		$obj->data_obj =& ThemeData();//new ThemeOptions(ThemeSetup::OPTIONS_KEY);
    		$obj->nonce = rand(1,99999);
    		//ovde treba 
    		$this->is_initialized = true;
	    }	
		return $this;
	}

	
	/**
	 * Retrieves loaded data from DB
	 */
	public function getDataDb(){
		return $this->data_obj->getAllOptions();
	}

	/**
	 * Retrive specific option.
	 * @param string $key
	 */
	public function getOneDb($key){
		return $this->data_obj->getOption($key);
	}
	
	protected function getMenuItems(){
	    return $this->data_obj->getMenuItems();
	}
	
	protected function getTabItems(){
	    return $this->data_obj->getTabItems();
	}
	
	/**
	 * Cemu ovo sluzi i da li se negde koristi?
	 * @param unknown_type $key
	 */
	public function defsLoaded($key){
	    
	    $r = $this->data_obj->defsLoaded($key);
	    return isset($r) ? true : false;
	}
    
	
    protected function file_ok($file){
        $r = $this->data_obj->file_ok($file);
	     return $r;   
	}
	
	public function setNotification($msg, $type){
		$this->notifications[] = array($msg, $type);
	}

	public function resetNotifications(){
		$this->notifications = array();
	}
	
	/**
	 * Niz Settings je niz n osnovu kog se iscrtava interface kada su u pitanju MAIN SETTINGS
	 * Za slidere sluzi samo kao data container.
	 * 
	 * Ovi nizovi su ovde prepopulisani sa podacima.
	 */
    public function displayAdminSettings()
    {
        /* MAIN SETTINGS TAB */
	    $ms_view['tab_items']          = $this->data_obj->getSetting('main_settings.data.tabs');
	    $ms_view['main_settings_menu'] = $this->data_obj->getSetting('main_settings.data.menu_items');
	    $tabs_content = array();

	    foreach ($ms_view['main_settings_menu'] as $t){
	        $ms_view['tabs_content'][$t['id']] = $this->data_obj->getSetting('main_settings.data.items.'.$t['id']);
	    }

			    
	    /* SLIDERS SETTINGS TAB */

	    /* Ovo mi treba bez obzira da li imamo neki slider u bazi ili ne */
	    if($this->data_obj->getSetting('sliders_tpl') == null){ 
	    	$slider_data = $this->data_obj->getSettingTpl('sliders_tpl.data.sliders');
	 		$sl_view_sliders  			 = $slider_data;//$this->data_obj->getSetting('sliders_tpl.data.sliders'); //
		    $sl_view_current_slider_type = 'cycle_slider';//$slider_data['incommon']['slider_type']['default'];
	    }else{
	    	$slider_data = $this->data_obj->getSetting('sliders_tpl.data.sliders');
	    	$sl_view_sliders  			 = $slider_data;//$this->data_obj->getSetting('sliders_tpl.data.sliders'); //
		    $sl_view_current_slider_type = $slider_data['current_slider'];//$slider_data['incommon']['slider_type']['value'];//$this->data_obj->getSetting('sliders_tpl.data.sliders.incommon.slider_type.value');
	    }
	   
		$sl_view_current_slider_name = $slider_data[$sl_view_current_slider_type]['incommon']['slider_name']['value'];
	    /*if($sl_view_current_slider_type === null){
	        $sl_view_current_slider_type = $this->data_obj->getSetting('sliders_tpl.data.sliders.incommon.slider_type.default');
	        $v = $this->data_obj->getSetting('sliders_tpl.data.sliders.incommon.slider_type.values');
	        $sl_view_current_slider_name = $v[$sl_view_current_slider_type];
	    }else{
	    	$v = $this->data_obj->getSetting('sliders_tpl.data.sliders.incommon.slider_type.values');
	    	$sl_view_current_slider_name = $v[$sl_view_current_slider_type];
	    }*/
		
	    if(file_exists(KIDS_ADMIN_VIEW_DIR . '/admin-panel.phtml') && is_readable(KIDS_ADMIN_VIEW_DIR . '/admin-panel.phtml')){
	            include_once KIDS_ADMIN_VIEW_DIR . '/admin-panel.phtml';
	     }else{
	        // echo "UPS!!! NO ADMINSTRATION. Templates missing!!!";
	     }
	}
	
	/**
	 * Alias for renderAdminPanelContent
	 * Enter description here ...
	 */
	public function panelMachine(){
	    $this->renderAdminPanelContent();
	}
	
	public function parseField($field = array(), $section=''){
	  
	    $output='';

	    if(!isset($field['default'])){ $field['default'] = null;}
	    
	    $value = (isset($field['value'])) ? $field['value'] : $field['default'];
	    
	    if ((isset($field['children']) && $field['children'] == true)) $has_children = 'data-haschildren="1"'; else $has_children = 'data-haschildren="0"';
	    
	     $depend 	= isset($field['depend']) ? $field['depend'] : '';
	     $depend_id = isset($field['depend_id']) ? $field['depend_id'] : '';
	     $parent_id = isset($field['parent_id']) ? 'data-parentid="'.$field['parent_id'].'"' : '';

	    if(is_array($value)){
	    	$value = array_map('stripslashes', $value);
	    }
	    else{
	    	$value = stripslashes($value);
	    }

	    if($section){ $section = $section . '__';}
	    
        switch ($field['type']){
	        
            case 'start_section':{
                $output .= '<h2>'. $field['name']. '&nbsp;<small>'. $field['desc']. '</small></h2>';
                break;
            }
            case 'start_sub_section':{
                $output .= '<div class="control-group theme-section well-no-shadow">
			    				<label class="control-label">'.$field['name'].'<br/></label>
			    			</div>';
                break;
            }
	        case 'text':{
	          
	            $output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
			    				<label class="control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    					<input id="'.$field['id'].'" type="text" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" class="span6" />
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
			    			</div>';
	                        break;
            }
            
            case 'list':{

                $options     = ($field['value']!='' && !is_array($field['value'])) ? array($field['value']) : $field['value'];
	            $options_li  = '';
	            $options_str = '';
	            
	            if(isset($options) && is_array($options)){
    	            foreach ($options as $v){
    	            		$options_str .= '<option value="'.esc_attr($v).'" selected="selected">'.esc_attr($v).'</option>';
    	            		$options_li .= '<li class="accordion-heading" data-sidebarid="'.esc_attr($v).'"> '.esc_attr($v).'<span><a href="#">×</a></span></li>';
    	            }
	            }
                $output_s = '<select style="display:none;" id="'.$section.$field['id'].'" name="'.$section.$field['id'].'" multiple="multiple">
			    				'.$options_str.'
			    			</select>';
                
                $output .= '<div class="control-group dinamic-field custom_multi_list '.$depend_id.'" '.$has_children.' '.$parent_id.'>
			    				<label class="control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    				'.$output_s.'
			    					<input id="_'.$field['id'].'" type="text" name="_'. $field['id'].'" class="span6" />
			    					<a id="add_'.$field['id'].'" class="btn" href="#">Add</a>
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
			    			</div>';
                
                 
	            $output .= '<ul id="list_'.$field['id'].'" class="admin-list">'.$options_li.'</ul>';
	            break;
            }
            case 'hidden':{
	            $output .= '<input class="dinamic-field" type="hidden" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" />';
	                break;
            }
	        case 'textarea':{

	            	$output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
				    				<label class="control-label">'.$field['name'].'</label>
				    				<div class="controls">
				    					<textarea id="'.$section.$field['id'].'" rows="4" name="'.$section . $field['id'].'" class="span8">'.esc_textarea($value).'</textarea>
				    					<p class="help-block">'.$field['desc'].'</p>
				    				</div>
				    			</div>';		
	           
	                break;
            }
	        case 'colorpicker':
	            
	            $output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
			    				<label class="control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    					<input id="'.$field['id'].'" type="text" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" class="mls_colorpicker" />
			    					<span class="add-on"><i></i></span>
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
			    			</div>';
	            break;
            
	        case 'number' : {
	        
            			    $output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
			    				<label class="control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    					<div class="number span6" data-step="'.$field['step'].'" data-min="'.$field['min'].'" data-max="'.$field['max'].'"></div>
			    					<input readonly="readonly" id="'.$field['id'].'" type="text" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" class="span2" />
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
			    			</div>';
	            
	            break;
	        }
            case 'checkbox':{

                $output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
			    				<label class=" control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    					<input name="'.$section . $field['id'].'" type="hidden" value="'.esc_attr($value).'" id="'.$field['id'].'"  />
			    					<div data-toggle="buttons-radio" class="btn-group">
			    						<a href="#" data-state="on" class="btn '.(($value == 'on') ? 'active' : '').'">ENABLED</a>
			    						<a href="#" data-state="off" class="btn '.(($value == 'off') ? 'active' : '').'">DISABLED</a>
			    					</div>
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
			    			</div>';
                break;
            }
            
            case 'radio': {
                
                $options = $field['values'];
	            $options_str = '';

	            foreach ($options as $k=>$v){
	                $selected = ($k == $value) ? 'active' : '';
	                $options_str .= '<a href="#" data-state="'.$k.'" class="btn '.$selected.'" >'.esc_attr($v).'</a>';
	            }
                
                $output .='<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
                			  <label class=" control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    					<input name="'.$section . $field['id'].'" type="hidden" value="'.esc_attr($value).'" id="'.$field['id'].'"  />
			    					<div data-toggle="buttons-radio" class="btn-group">
			    					'.$options_str.'
			    					</div>
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
                		   </div>';
                break;
            }
	        case 'file':{

	        		if($value == '') $value = $field['default'];

	            	$output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
				    				<label class="control-label">'.$field['name'].'<br/></label>
				    				<div class="controls">
				    					<input type="hidden" id="'.$field['id'].'" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" />
				    					<div class="btn-group uploader">
				    						<a href="#" name="f_'.$field['id'].'" class="btn active filename span8">'.esc_attr($value).'</a>
				    						<a href="#" class="btn action">Upload File</a>
				    					</div>
				    					<p class="help-block">'.$field['desc'].'</p>
				    				</div>    
				    			</div>';		
	                break;
            }
	        case 'select':{
	            
	            $options     = $field['values'];
	            $options_str = '';

	            foreach ($options as $k=>$v){
	                $selected = ($k == $value) ? 'selected' : '';
	                $options_str .= '<option value="'.esc_attr($k).'" '.$selected.' >'.esc_attr($v).'</option>';
	            }
	                         
	            $output .='<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
    				<label class="control-label">'.$field['name'].'</label>
    				<div class="controls">
    					<select id="'.$field['id'].'" name="'.$section . $field['id'].'">
    						'.$options_str.'
    					</select>
    					<p class="help-block">'.$field['desc'].'</p>
    				</div>
    			</div>';		   
                break;
            }
             case 'multiselect':{

             	if(!is_array($value)){ $value = array($value);}
	            
	            $options     = $field['values'];
	            $options_str = '';

	            foreach ($options as $k=>$v){

	            	if(in_array($k, $value)){
	            		$options_str .= '<option value="'.esc_attr($k).'" selected>'.esc_attr($v).'</option>';
	            	}
	            	else{
	            		$options_str .= '<option value="'.esc_attr($k).'">'.esc_attr($v).'</option>';
	            	}
	            }  
	            
	            $output .= '<div class="control-group dinamic-field '.$depend_id.'" '.$has_children.' '.$parent_id.'>
                                <label for="multiSelect" class="control-label">'.$field['name'].'</label>
                                <div class="controls">
                                  <select name="'.$section . $field['id'].'" id="'.$field['id'].'" multiple="multiple">
                                   '.$options_str.'
                                  </select>
                                  <p class="help-block">'.$field['desc'].'</p>
                                </div>
                              </div>';		   
	                break;
            }
	    }//Switch End
	    return $output;
	}
	
    public function parseFieldForMetaBox($field = array(), $section=''){
	    
	    $output='';
	    $value = (isset($field['value'])) ? $field['value'] : $field['default'];

	    if(is_array($value)){
	    	$value = array_map('stripslashes', $value);
	    }
	    else{
	    	$value = stripslashes($value);
	    }
	    
        switch ($field['type']){
	        
            case 'start_section':{
                $output .= '<h4>'. $field['name']. '&nbsp;-&nbsp;<small>'. $field['desc']. '</small></h4>';
                break;
            }
	        case 'text':{
	          
	            $output .= '<p class="inside"><label>'.$field['name'].'</label>
			    			<input id="'.$section.$field['id'].'" type="text" name="'.$section . $field['id'].'" value="'.trim(esc_attr($value)).'" style="margin-right:10px; width:50%" />
			    			<span class="howto">'.$field['desc'].'</span></p>';
	            break;
            }
            case 'hidden':{
	            $output .= '<input class="dinamic-field" type="hidden" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" />';
	                break;
            }
	        case 'textarea':{
	            	$output .= '<div class="control-group dinamic-field">
				    				<label class="control-label">'.$field['name'].'</label>
				    				<div class="controls">
				    					<textarea id="'.$section.$field['id'].'" rows="4" name="'.$section . $field['id'].'" class="span8">'.trim(esc_textarea($value)).'</textarea>
				    					<p class="help-block">'.$field['desc'].'</p>
				    				</div>
				    			</div>';		
	                break;
            }
            case 'checkbox':{
                $output .= '<div class="control-group dinamic-field">
			    				<label class=" control-label">'.$field['name'].'<br/></label>
			    				<div class="controls">
			    					<input name="'.$section . $field['id'].'" type="hidden" value="'.esc_attr($value).'" id="'.$field['id'].'"  />
			    					<div data-toggle="buttons-radio" class="btn-group">
			    						<a href="#" data-state="on" class="btn '.(($value == 'on') ? 'active' : '').'">ENABLED</a>
			    						<a href="#" data-state="off" class="btn '.(($value == 'off') ? 'active' : '').'">DISABLED</a>
			    					</div>
			    					<p class="help-block">'.$field['desc'].'</p>
			    				</div>
			    			</div>';
                break;
            }
	        case 'file':{
	        		if($value == '') $value = $field['default'];
	            	$output .= '<div class="control-group dinamic-field">
				    				<label class="control-label">'.$field['name'].'<br/></label>
				    				<div class="controls">
				    					<input type="hidden" id="'.$field['id'].'" name="'.$section . $field['id'].'" value="'.esc_attr($value).'" />
				    					<div class="btn-group uploader">
				    						<a href="#" name="f_'.$field['id'].'" class="btn active filename span8">'.esc_attr($value).'</a>
				    						<a href="#" class="btn action">Upload File</a>
				    					</div>
				    					<p class="help-block">'.$field['desc'].'</p>
				    				</div>    
				    			</div>';		
	                break;
            }
	        case 'select':{
	            $options = $field['values'];
	            $options_str = '';

	            foreach ($options as $k=>$v){
	                $selected = ($k == $value) ? 'selected' : '';
	                $options_str .= '<option value="'.esc_attr($k).'" '.$selected.' >'.esc_attr($v).'</option>';
	            }
	                         
	            $output .='<div class="control-group dinamic-field">
    				<label class="control-label">'.$field['name'].'</label>
    				<div class="controls">
    					<select id="'.$field['id'].'" name="'.$section . $field['id'].'">
    						'.$options_str.'
    					</select>
    					<p class="help-block">'.$field['desc'].'</p>
    				</div>
    			</div>';		   
                break;
            }
             case 'multiselect':{
             	if(!is_array($value)){ $value = array($value);}
	            
	            $options = $field['values'];
	            $options_str = '';

	            foreach ($options as $k=>$v){

	            	if(in_array($k, $value)){
	            		$options_str .= '<option value="'.esc_attr($k).'" selected>'.esc_attr($v).'</option>';
	            	}
	            	else{
	            		$options_str .= '<option value="'.esc_attr($k).'">'.esc_attr($v).'</option>';
	            	}
	            }  
	         
	            $output .= '<div class="control-group dinamic-field">
                                <label for="multiSelect" class="control-label">'.$field['name'].'</label>
                                <div class="controls">
                                  <select name="'.$section . $field['id'].'[]" multiple="multiple">
                                   '.$options_str.'
                                  </select>
                                  <p class="help-block">'.$field['desc'].'</p>
                                </div>
                              </div>';		   
	                break;
            }
	    }//Switch End
	    return $output;
	}
	
	public function renderTabButtons(){
	    $tpl = '<li><a data-toggle="tab" href="#%s">%s</a></li>';
	    $output = '';
	    $ttt = $this->data_obj->getSetting('main_settings.data.tabs');
	    foreach ($ttt as $tab) {
	        $output .= sprintf($tpl, $tab['id'], $tab['name']);
	    }
	    return '<ul id="main_navigation" class="nav nav-pills">' . $output .'</ul><!-- main_navigation -->';
	}
		
	public function getSetting($path = null) {
	    return $this->data_obj->getSetting($path);
    } 
    
     
    
    /**
     * This is main Ajax Handler. All ajax request fired in theme administration should be handled by
     * this method.
     * 
     *  PReko posta dobije niz sledeci
     *  
     *  main_settings=>arraY(name => '', value= ''), array(name => '', value= '') ..... array(name => '', value= '')
     *  sliders => ....
     *  
     */
    public function adminAjax()
    {
        $action = sanitize_text_field($_POST['sec_action']);
        // check to see if the submitted nonce matches with the
        // generated nonce we created earlier
        $nonce = check_ajax_referer(ThemeSetup::HOOK . '-admin-ajax-nonce', ThemeSetup::HOOK . '_admin_ajax_nonce', false);
        
        if ($nonce === false){
            die();
        }

        $resp = $_POST; 

        switch ($action){

            case 'save': {// in case of saving data old data will be overwritten and/or new data will be created.

                $validator_main = new Validator(null, $resp['main_settings']);
                if(is_array($resp['main_settings'])){
                    //Prepare Validation array related to a main settings.
                    $validator_main->prepareMap($this->data_obj->getSettingTpl('main_settings.data.items'));        
                    if($validator_main->validate()){
                        $validator_main->applyFilters();
                    }
                }

                //Sections of interest
                if(!$validator_main->hasErrors()){
				  $this->data_obj->setOption('main_settings', $validator_main->data);
                   $response = $this->data_obj->save();
                }
                else{
                   $response = false;
                }
               
                if($response == true){
				   $response = array('status'=>'success', 'msg'=>'Success. Data saved!');
                }
                else{
                   $response = array('status'=>'fail', 'msg'=>'Options was not changed. '.$validator_main->toString());
                }
                break;
            }
            case 'save_slider' : {
                
            	$validator_sliders = new Validator(null, $resp['sliders']);
            	
                if(is_array($resp['sliders'])){
                	$type = $resp['sliders']['incommon']['slider_type'];
                	if(isset($type) && $type != ''){
                		$validator_sliders->prepareMapSliders($this->data_obj->getSettingTpl('sliders_tpl.data.sliders'), $type);
	                    if($validator_sliders->validate()){
	                    	$validator_sliders->applyFilters();
	                    }

	                    if(!$validator_sliders->hasErrors()){ 
						  $this->data_obj->setOption('collections.sliders.current_slider', $type);
						  $this->data_obj->setOption('collections.sliders.data.'.$type, $validator_sliders->data);
		                  $response = $this->data_obj->save();
		                }else{ 
		                	 $response = false;
		                }
                	}
                	else{
                		$response = false;
                	}
                }

                if($response == true){
				   $response = array('status'=>'success', 'msg'=>'Success. Data saved!');
                }
                else{
                    if($validator_sliders->hasErrors()){
                        $response = array('status'=>'fail', 'msg'=>current($validator_sliders->errors));
                    }else{
                        $response = array('status'=>'fail', 'msg'=>'Options was not changed. Slider data is not saved.');
                    }
                }
                break;
            }
            case 'load_sliders':{
                $response = $this->data_obj->getSetting('sliders_tpl.data.sliders');
               // var_dump($response);
                $response['tpls'] = $this->data_obj->getSettingTpl('sliders_tpl.data.sliders');
                break;
            }
        }
        
        $response = json_encode($response);
        header("Content-Type: application/json");
        die($response);
    }
}