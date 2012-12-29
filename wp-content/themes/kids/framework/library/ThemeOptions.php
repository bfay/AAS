<?php
/**
 *   Options Management Class 
 *   
 *   Manage all bussiness logic related to insert, update, delete data from DB.
 *   
 *   
 *   1. Instalacija Prilikom instalacije upisujem samo meta podatke u bazu. Datum instalacije i verziju!
 *   2. Inace
 *   
 *        Ucitam konf. iz fajlova.
 *        Ucitam podatke iz DB.
 *        Populisem settings niz.
 *        
 *        U prikazu sve vrednosti se prikazuju preko fje koje proverava ako je "value" NULL onda ide default vrednost.
 *        Dakle DEFAULT vrednost se prikazuje sve do onog trenutka dok korisnik eksplicitno ne izabere neku opciju! Tj. do prvog
 *        pritiska na dugme SAVE!
 *
 */

 /*
 	Standalone Class used by ThemeSetup and ThemeAdmin classes.

 		Saves, Deletes, Updates Theme Options.
 		Restores options to their default states.

 */

 class ThemeOptions {
 	
 	/* All options form DB for option key $option_key */
 	protected $options 		= array();

 	/* Under this key we are saving option in DB. */
 	protected $option_key  = null;

	/* Defaults loaded from files */
	public $conf_files = array();
	
    /**
     * Flag
     */
	protected $conf_files_loaded = false;

    /**
     *  Array indexes related to a form data. This is 
	 */
	public $data_index = array('mi_look_feel','mi_blog','mi_social','mi_contact','mi_google_analitics','mi_header','mi_footer','mi_misc','mi_gmaps','mi_custom_sidebars');
	
    /**
     *  Default settings loaded form config files [$conf_files]. This array could be considered as template
     */
    public $settings_tpl = array();

    /**
     *  Settings loaded from config file, but also populated with data form DB 
     */
    public $settings = array();
	
	/* All errors */
	public $errors = array();
	
	 	
	/**
	 *  Constructor
	 */
 	public function __construct($option_key){
 		$this->option_key = $option_key;
 		$this->options    = get_option($this->option_key, 
    	                                array('meta' => array(),
    	                                      'main_settings' => array(),
    	                                      'collections' => array('sliders'=>array()))
    	                                );
	    $this->loadConfFiles();

	    if($this->optionsLoaded()){  
	        $this->setValuesFromDB();
	    }   
 	}
 	 	
	protected function loadConfFiles(){

	    if($this->conf_files_loaded === true) return ; 
        try{
    		$this->loadSettings(KIDS_CONFIG_DIR . '/' . 'default_theme_options.php');
    		$this->loadSettings(KIDS_CONFIG_DIR . '/' . 'default_slider_options.php');
    		
    		//Set tpl structure to real struture. Real structure will be updateded with data from DB. In case there is no sliders. Those data
            //will be unset from settings arr
    		$inc = $this->settings_tpl['sliders_tpl']['data']['sliders']['incommon']; 
    		
    		$this->settings_tpl['sliders_tpl']['data']['sliders']['current_slider'] = '';
    		$this->settings_tpl['sliders_tpl']['data']['sliders']['piece_slider']['incommon'] = $inc;
    		$this->settings_tpl['sliders_tpl']['data']['sliders']['cycle_slider']['incommon'] = $inc;
    		//exit;
    		unset($this->settings_tpl['sliders_tpl']['data']['sliders']['incommon']);
    		
            $this->settings = $this->settings_tpl;
            unset($this->settings['sliders_tpl']['data']['sliders']['cycle_slider']['item_data']);
            unset($this->settings['sliders_tpl']['data']['sliders']['piece_slider']['item_data']);

    		$this->conf_files_loaded = true;
		}catch (FrameworkException $e){
		    $this->errors[] = 'Unable to load settings. : ' . $e->getMessage();
		}
 	}
	
  	
 	/**
 	 * Inject data in to $this->settings arrays. 
 	 * Apply all settings from DB to Settings array
 	 * 
 	 *  Rebuild Main Settings
 	 *  Rebuil Created Sliders
 	 */
 	protected function setValuesFromDB(){
 	    $types_to_replace = array('text', 'select', 'file', 'checkbox', 'radio', 'textarea', 'date', 'multiselect', 'number', 'hidden', 'list');
 	    
 	    /* !IMPORTANT! Variable $main is POINTER to a one part od settings array!!! */
        $main = &$this->getSetting('main_settings.data.items'); // returns main settings data from conf file!
        foreach ($main as $key => $section) {
            foreach ($section as $kk => $value){
 	            if(isset($value['type']) && in_array($value['type'], $types_to_replace)){
 	                $main[$key][$kk]['value'] = $this->getOption('main_settings.'.$key.'.'.$value['id']);
 	            }
            }
        }

        //Get sliders from DB
        $sliders = $this->getOption('collections.sliders');

        //Get sliders configurations
        //$sliders_conf = $this->getSlidersTpls(); //sliders conf from file!

        if(!empty($sliders))
        {
           /* Id of active slider */
           $current_slider_type = $sliders['current_slider'];
         //  $current_slider      = $sliders['data'][$current_slider_type];//[$slider_id];
           //var_dump($sliders['data']);
          // var_dump($this->settings);
           foreach ($sliders['data'] as $slider_key => $slider){
                //Set Settings related to a slider  
                
               // var_dump($this->settings['sliders_tpl']['data']['sliders']['incommon']);
               // $this->settings['sliders_tpl']['data']['sliders'][$slider_key]['incommon'] = $this->settings['sliders_tpl']['data']['sliders']['incommon'];
                $settings   =& $this->settings['sliders_tpl']['data']['sliders'][$slider_key]['settings'];
                $transition =& $this->settings['sliders_tpl']['data']['sliders'][$slider_key]['transition'];
                $incommon   =& $this->settings['sliders_tpl']['data']['sliders'][$slider_key]['incommon'];
                
                foreach ($incommon as $k => $field) {
                    //Da li je ova vrednost definisana u bazi
                    if(isset($slider['incommon'][$k])){
                        $incommon[$k]['value'] = $slider['incommon'][$k];
                    }
                }
                
                foreach ($settings as $k => $field) {
                    //Da li je ova vrednost definisana u bazi
                    if(isset($slider['settings'][$field['id']])){
                            $settings[$k]['value'] = $slider['settings'][$field['id']];
                    }
                }
                
                if(is_array($transition) && !empty($transition)){
                    foreach ($transition as $k => $field) {
                        //Da li je ova vrednost definisana u bazi
                        if(isset($slider['transition'][$field['id']])){
                                $transition[$k]['value'] = $slider['transition'][$field['id']];
                        }
                    }
                }
                
                $this->settings['sliders_tpl']['data']['sliders'][$slider_key]['item_data'] = array();
                
                if(isset($slider['item_data']) && is_array($slider['item_data']) && count($slider['item_data']) > 0){
                    foreach ($slider['item_data'] as $key => $value) {//idem po slajdovima
                        //idem po opcijama za slide
                        $slide_tpl = $this->settings_tpl['sliders_tpl']['data']['sliders'][$slider_key]['item_data'];
                        foreach ($slide_tpl as $k => $tpl) {
                            # code...
                            $slide_tpl[$k]['value'] = $value[$tpl['id']];
                        }
                        $this->settings['sliders_tpl']['data']['sliders'][$slider_key]['item_data'][] = $slide_tpl; //Push new slide
                    }
                }     
           }//foreach

           unset($this->settings['sliders_tpl']['data']['sliders']['incommon']);
           //Do we have at least one slide created
            $this->settings['sliders_tpl']['data']['sliders']['current_slider'] = $current_slider_type; 
        }else{
            //Remove template for a slide form array related to builting slider form, because we do not have one!.
            unset($this->settings['sliders_tpl']);
        }
 	}
 	
 	/**
 	 * Prepare / Format $this->options from $this->settings array. 
 	 * Rebuilt options array for SAVE / EDIT ... purpose!!!
 	 */
 	public function filterSettingsForDb($filterMain=true, $filterSliders = true){
 	    $types_to_replace = array('text', 'select', 'file', 'checkbox', 'textarea', 'date', 'multiselect', 'number', 'hidden', 'list');

        if($filterMain == true){
            $main = $this->getSetting('main_settings.data.items'); // returns main settings data
            $tmp = null;
            foreach ($main as $key => $section) { //iteriram po svim sekcijama
                foreach ($section as $kk=>$value){ //iteriram po poljima u sekciji
                    if(isset($value['type']) && in_array($value['type'], $types_to_replace)){ //Ako je polje koje treba snimiti u bazu
                        $tmp = ($value['value']!== null && $value['value']!== '') ? $value['value'] : $value['default'];
                        $this->setOption('main_settings.'.$key.'.'.$value['id'], $tmp);
                        $tmp = null;
                    }
                }     
            }
        }

        /**
         * In case there is no sliders. Do nothing. Sliders do not require any initializations.
         */
         if($filterSliders == true){
            $sliders = &$this->getSetting('sliders_tpl');
         }
 	}
 	
 	
     /**
 	 * Loads Default setting from conf files.
 	 * @param string $file
 	 * @param boolean $overwrite
 	 */
     protected function loadSettings($file, $overwrite = true){
	      if(file_exists($file) && is_readable($file)){ 
             require_once $file;
             if(isset($meta_data) && is_array($meta_data) && $meta_data['name']!=''){
                 if($overwrite){ 
                      $this->settings_tpl[$meta_data['name']] = array('meta' => $meta_data, 'data' => $options);
                 }
             }
             else{
                 $this->errors[$file] = 'Config file corrupted!';
             }
         }   
         else{
             $this->errors[$file] = 'Unable to read file' .$file.'. Or file missing!';
         }
	}
	
	
    /**
     * [$path description]
     * @var [type]
     */
	public function &getSetting($path = null) {
	    reset($this->settings);
	    $null = null;
        if($path === null) {
            return $this->settings;
        }
        $segs = explode('.', $path);
        $target =& $this->settings;
        for($i = 0; $i < count($segs)-1; $i++) {
            
            if(isset($target[$segs[$i]]) && is_array($target[$segs[$i]])) {
                $target =& $target[$segs[$i]];
            } else {
                return $null; //Stupid fix to suppress Notice Error
            }
        }
        if(isset($target[$segs[count($segs)-1]])) {
            return $target[$segs[count($segs)-1]];
        } else {
            return $null; //Stupid fix to suppress Notice Error
        }
    } 


    public function &getSettingTpl($path = null) {
        reset($this->settings_tpl);
        
        if($path === null) {
            return $this->settings_tpl;
            exit;
        }
        $segs = explode('.', $path);
        $target =& $this->settings_tpl;
        for($i = 0; $i < count($segs)-1; $i++) { 
            if(isset($target[$segs[$i]]) && is_array($target[$segs[$i]])) {
                $target =& $target[$segs[$i]];
            } else {
                return null;
            }
        }
        if(isset($target[$segs[count($segs)-1]])) {
            return $target[$segs[count($segs)-1]];
        } else {
            return null;
        }
    } 
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $key
     */
  	protected function &getMenuItems(){
	    return $this->settings['main_settings']['data']['menu_items'];
	}
	
	protected function &getTabItems(){
	    return $this->settings['main_settings']['data']['tabs'];
	}
	
	protected function &getMainSettingsFormElements(){
	    return $this->settings['main_settings']['data']['items'];
	}
	
	protected function getSlidersTpls(){
	    return $this->settings_tpl['sliders_tpl'];
	}
	
	/**
	 * Cemu ovo sluzi i da li se negde koristi?
	 * @param unknown_type $key
	 */
	public function defsLoaded($key){
	    return isset($this->settings[$key]); 
	}
	
     public function file_ok($file){
	     return file_exists($file) && is_readable($file);   
	}
	
	//DB realted staff
 	public function optionsLoaded(){
 		return (is_array($this->options['main_settings']) && count($this->options['main_settings']) > 0);
 	}

    /**
     * This methid is called only once. When theme is installing. Load def . vals. Set data to structure proledjuje na snimanje
     */  	
 	public function setOptionsDefaults()
 	{
 	    $this->setOption('meta.version', ThemeSetup::THEME_VERSION);

 	    if(!$this->hasErrors()){
 	           $this->filterSettingsForDb(true, false);
 	    }
 	    return $this;
 	}
 	
 	/**
	 * Saves ALL the internal options data to the wp_options table using the stored $option_key value as the key
	 *
	 * @return boolean
	 */
	public function save()
	{
	    //Delete old data set new data
	    return update_option($this->option_key, $this->options);
	}
	
	/**
	 * Deletes the internal options data from the wp_options table.
	 * This method is intended to be used as part of the uninstall process.
	 *
	 * @return boolean
	 */
	public function delete()
	{
		return delete_option($this->option_key);
	}

    /**
     * Alias for __set
     * @param mixed $path (string or array)
     * @param mixed $value
     */
	public function setOption($path, $value){
	    return $this->__set($path, $value);
	}
	
	public function __set($path, $value = null) {
            if(is_array($path)) {
                foreach($path as $p => $v) {
                    $this->__set($p, $v);
                }
            } else {
                $segs = explode('.', $path);
           
                $target =& $this->options;
                for($i = 0; $i < count($segs)-1; $i++) {
                    if(!isset($target[$segs[$i]])) {
                        $target[$segs[$i]] = array();
                    }
                    $target =& $target[$segs[$i]];
                }
                $target[$segs[count($segs)-1]] = $value;
            }
            return  $this;
    } 
	
    /**
     * Alias for __get
     * @param string $param
     */
    public function getOption($param=null){
        return $this->__get($param);
    }

    /* These Two ,ethods sholuld be used on View ALWAYS */
    public function getMain($opt = null){
        $opt = isset($opt) ? 'main_settings.' . $opt : 'main_settings';
        return $this->__get( $opt);
    }
    
    public function echoMain($opt = null){
        $opt = isset($opt) ? 'main_settings.' . $opt : 'main_settings';
        echo stripslashes($this->__get( $opt));
    }
    
    public function isOn($opt){
        if($this->getMain($opt) == 'on'){
            return true;
        }else{
            return false;
        }
    }

    public function getSliderOpts(){
        return $this->options['collections']['sliders'];
    }
    
    public function getSldr($path=''){
        if($path) $path = '.' . $path;  
        return $this->__get('collections.sliders' . $path);
    }
    
    public function getCurrentSlider(){
        return $this->__get('collections.sliders.current_slider');
    }
    
	/**
     * Gets the value at the specified path.
     */
    public function __get($path = null) {
        if($path === null) {
            return $this->options; 
            exit;
        }
       
        $segs = explode('.', $path);
       
        $target =& $this->options;
        for($i = 0; $i < count($segs)-1; $i++) {
            if(isset($target[$segs[$i]]) && is_array($target[$segs[$i]])) {
                $target =& $target[$segs[$i]];
            } else {
                return null;
            }
        }
       
        if(isset($target[$segs[count($segs)-1]])) {
            return $target[$segs[count($segs)-1]];
        } else {
            return null;
        }
    } 
	
    /**
     * Returns a flattened version of the data (one-dimensional array
     * with dot-separated paths as its keys).
     */
    public function flatten($path = null) {
        $data = $this->__get($path);
       
        if($path === null) {
            $path = '';
        } else {
            $path .= '.';
        }
       
        $flat = array();
                   
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $flat += $this->flatten($path.$key);
            } else {
                $flat[$path.$key] = $value;
            }
        }
        return $flat;
    }
   
    /**
     * Expands a flattened array to an n-dimensional matrix.
     */
    public static function expand($flat) {
        $matrix = new self();
       
        foreach($flat as $key => $value) {
            $matrix->__set($key, $value);
        }
        return $matrix;
    }     

	
	public function getAllOptions()
	{
		return $this->__get();
	}
	
	public function hasErrors(){
		return !empty($this->errors);
	}

	public function getErrors(){
		return $this->errors;
	}
	
	public function setError($msg){
	    $this->errors[] = $msg;
	}

	/**
	 * @todo Implement it :)
	 */
	public function _toString(){}
 }