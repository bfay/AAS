<?php
class Validator{
    
    public static $instance = null;
    
    public $errors = array();
    
    public $map = array();
    
    //This filter is applied to all data
    public $filters = array('trim'=>'trim');
    
    public $apply_filters_flag = true;

    /* Reference to input data fubmited from a form */
    public $data = null;
    
    
    public function  __construct($map, &$data){
        $this->map  =& $map;
        $this->data =& $data;
    }
    
    public function  __clone(){}
    
    
    /**
     * Niz polja u kojima su definisali validatori i filteri
     * 
     * Podaci iz POST-a
     * 
     * Kako odratiti strukturi
     * 
     * @param unknown_type $map
     * @param unknown_type $data
     */
    public function getInstance($map, &$data){
        
        if(self::$instance === null){
            $obj = new Validator();
        }else {
            $obj =& self::$instance;
        }
        
        $obj->map  =& $map;
        $obj->data =& $data;
        return $obj;
    }
    
    public function resetMap(){
        $this->map = null;
    }

    
    public function prepareMap($settings){
    
        $map = array();
        /**
         *  field_id => array('validators'=> array(), 'filters'=>array())
         */
        $types_to_replace = array('text', 'select', 'file', 'checkbox','radio', 'textarea', 'date', 'multiselect', 'number', 'hidden', 'list');
       
        foreach ($settings as $section => $setting){
            foreach ($setting as $value) {
                if(isset($value['type']) && in_array($value['type'], $types_to_replace)){ //ako je polje za validaciju
                    $this->map[$section][$value['id']] = array();

                    $this->map[$section][$value['id']]['type'] = $value['type'];

                    if(!isset($this->data[$section][$value['id']])){
                       //Polje nije postovano sa forme => To znaci da je/su u pitanju
                       //1. Checkbox koji nije stikliran
                       //2. Multiselect u kom nista nije odabrano
                       //3. Radio Button  koji nije stikliran
                        $this->data[$section][$value['id']] = '';
                    }
                    
                    if($value['type'] == 'multiselect' && !is_array($this->data[$section][$value['id']])){
                        if(isset($this->data[$section][$value['id']])){
                            $this->data[$section][$value['id']] = array($this->data[$section][$value['id']]);
                        }else{
                            $this->data[$section][$value['id']] = array('empty');
                        }
                    }

                    if(isset($value['required']) && $value['required']){
                        $this->map[$section][$value['id']]['required'] = $value['required'];
                    }

                    if(isset($value['validators'])){
                        $this->map[$section][$value['id']]['validators'] = $value['validators'];
                    }
                    
                    if(isset($value['filters'])){
                        $this->map[$section][$value['id']]['filters']    = $value['filters'];
                    }
                }
            }
        } 
    }

    /**
     * This function is used when user trigger SAVE SLIDER action. We are maping this so we can validate and apply filters on input data
     * 
     * @todo We could format config arrays that way so we do NOT need this mapping at all!> Now is too late :)
     * @param  array $settings    settings from template for sliders
     * @param  string $slider_type slider type
     * @return void [nothing]
     * 
     */
    public function prepareMapSliders($settings, $slider_type){
        $map = array();
        /**
         *  field_id => array('validators'=> array(), 'filters'=>array())
         */
        $types_to_replace  = array('text', 'select', 'file', 'checkbox','radio', 'textarea', 'date', 'multiselect', 'number', 'hidden', 'list');

        $incommon_settings = $settings[$slider_type]['incommon'];
        $sl_settings       = $settings[$slider_type]['settings'];
        $slides            = $settings[$slider_type]['item_data'];
        $transitions       = isset($settings[$slider_type]['transition']) ? $settings[$slider_type]['transition'] : array();

        if($slider_type){
            /* Incommon staff */
            foreach ($incommon_settings as $key => $setting) { // Ide po poljima
                if(isset($setting['type']) && in_array($setting['type'], $types_to_replace)){
                    if($setting['type'] == 'multiselect' && !is_array($this->data['incommon'][$setting['id']])){
                        if(isset($this->data['incommon'][$setting['id']])){
                            $this->data['incommon'][$setting['id']] = array($this->data['incommon'][$setting['id']]);
                        }else{
                            $this->data['incommon'][$setting['id']] = array('empty');
                        }
                    }

                    if(!isset($this->data['incommon'][$setting['id']])){
                        $this->data['incommon'][$setting['id']] = $setting['default'];
                    }
                    
                    $this->map['incommon'][$setting['id']]['id'] = $setting['id']; // this is VERY ODD. 
                    $this->map['incommon'][$setting['id']]['id']['type'] = $setting['type'];

                    if(isset($setting['required']) && $setting['required']){
                            $this->map['incommon'][$setting['id']]['required'] = $setting['required'];
                    }

                    if(isset($setting['validators'])){
                        $this->map['incommon'][$setting['id']]['validators'] = $setting['validators'];
                    }
                            
                    if(isset($setting['filters'])){
                        $this->map['incommon'][$setting['id']]['filters'] = $setting['filters'];
                    }
                }    
            }       

            /* Slider Options */
            foreach ($sl_settings as $key => $setting){
                if(isset($setting['type']) && in_array($setting['type'], $types_to_replace)){

                    $this->map['settings'][$setting['id']]['id'] = $setting['id'];

                    if($setting['type'] == 'multiselect' && !is_array($this->data['settings'][$setting['id']])){
                        if(isset($this->data['settings'][$setting['id']])){
                            $this->data['settings'][$setting['id']] = array($this->data['settings'][$setting['id']]);
                        }else{
                            $this->data['settings'][$setting['id']] = array('empty');
                        }
                    }

                    if(!isset($this->data['settings'][$setting['id']])){
                        $this->data['settings'][$setting['id']] = $setting['default'];
                    }

                    if(isset($setting['required']) && $setting['required']){
                        $this->map['settings'][$setting['id']]['required'] = $setting['required'];
                    }
                    if(isset($setting['validators'])){
                        $this->map['settings'][$setting['id']]['validators'] = $setting['validators'];
                    }
                    if(isset($setting['filters'])){
                        $this->map['settings'][$setting['id']]['filters']    = $setting['filters'];
                    }
                }
            }  


            /* Slider's Slides */

             foreach ($slides as $key => $setting){
                if(isset($setting['type']) && in_array($setting['type'], $types_to_replace) && isset($this->data['item_data']) && count($this->data['item_data']) > 0){

                    if(isset($setting['required']) && $setting['required']){
                        $this->map['item_data'][$setting['id']]['required'] = $setting['required'];
                    }

                    $this->map['item_data'][$setting['id']]['id'] = $setting['id'];
                    
                    if(isset($setting['validators'])){
                        $this->map['item_data'][$setting['id']]['validators'] = $setting['validators'];
                    }
                    
                    if(isset($setting['filters'])){
                        $this->map['item_data'][$setting['id']]['filters']    = $setting['filters'];
                    }
                }elseif (!isset($this->data['item_data']) || count($this->data['item_data']) == 0){
                    $this->setError('item_data', 'Slider must have at least one slide.');
                }
            }

            foreach ($transitions as $setting) {
                if(isset($setting['type']) && in_array($setting['type'], $types_to_replace)){

                    $this->map['transition'][$setting['id']]['id'] = $setting['id'];

                    if($setting['type'] == 'multiselect' && !is_array($this->data['transition'][$setting['id']])){
                        if(isset($this->data['transition'][$setting['id']])){
                            $this->data['transition'][$setting['id']] = array($this->data['transition'][$setting['id']]);
                        }else{
                            $this->data['transition'][$setting['id']] = array('empty');
                        }
                    }

                    if(!isset($this->data['transition'][$setting['id']])){
                        $this->data['transition'][$setting['id']] = $setting['default'];
                    }

                    if(isset($setting['required']) && $setting['required']){
                        $this->map['transition'][$setting['id']]['required'] = $setting['required'];
                    }
                    if(isset($setting['validators'])){
                        $this->map['transition'][$setting['id']]['validators'] = $setting['validators'];
                    }
                    if(isset($setting['filters'])){
                        $this->map['transition'][$setting['id']]['filters']    = $setting['filters'];
                    }
                }
            }
        }
    }


    
    /**
     * Validation of an input data
     * @return [type]
     */
    public function validate(){
       
        if(isset($this->data['incommon'])){ // we are dealing with sliders
            if(isset($this->data['item_data'])){ 
                foreach($this->data['item_data'] as $i => $slide){ // By slides
                    foreach ($this->map['item_data'] as $id => $value) { //by fields
                        if(isset($value['required']) && $value['required'] ===true){
                             if(!isset($this->data['item_data'][$i][$id]) || $this->data['item_data'][$i][$id]=='' || empty($this->data['item_data'][$i][$id])){
                                 $this->setError($id, 'Required data.');
                             }
                        }  
    
                       if(!$this->fieldHasErrors($id)){
                           //Proceede with validation
                           if(isset($this->data['item_data'][$i][$id]) && $this->data['item_data'][$i][$id]!='' && !empty($this->data['item_data'][$i][$id])){
                                 if (isset($value['validators']) && is_array($value['validators'])){
                                       foreach ($value['validators'] as $validator){
                                            if(!$this->{$validator}($this->data['item_data'][$i][$id])){
                                                $this->setError($id, 'NOT VALID');
                                            }
                                       }
                                 }                       
                           }else{
                                /* Slucaj kada je u pitanju checkbox ili recomp multiselect a te opcije nisu stiklirane. */
                                //Nece biti upisane u bazu pa ce prilikom ucitavanja biti prikazama default vrednost!
                                if($value['type'] == 'multiselect' && !is_array($this->data['item_data'][$i][$value['id']])){
                                    if(isset($this->data['item_data'][$i][$value['id']])){
                                        $this->data['item_data'][$i][$value['id']] = array($this->data['item_data'][$i][$value['id']]);
                                    }else{
                                        $this->data['item_data'][$i][$value['id']] = array('empty');
                                    }
                                }
    
                                if(!isset($this->data['item_data'][$i][$value['id']])){
                                    $this->data['item_data'][$i][$value['id']] = $value['default'];
                                }
                           }
                        }
                    }// by fields
                } //end foreach for slides
            }
            
           foreach ($this->map as $sec_key=>$section)  // by sections
           { 
                if($sec_key == 'item_data') continue; // Ovo razmatramo posebno

               // echo "SETION: " . $sec_key ."\n";
                foreach ($section as $id => $value) { //by fields
                    if(isset($value['required']) && $value['required'] ===true){
                         if(!isset($this->data[$sec_key][$id]) || $this->data[$sec_key][$id]=='' || empty($this->data[$sec_key][$id])){
                             $this->setError($id, 'Required data.');
                         }
                    }  

                   if(!$this->fieldHasErrors($id)){
                       //Proceede with validation
                       if(isset($this->data[$sec_key][$id]) && $this->data[$sec_key][$id]!='' && !empty($this->data[$sec_key][$id])){
                             if (isset($value['validators']) && is_array($value['validators'])){
                                   foreach ($value['validators'] as $validator){
                                        if(!$this->{$validator}($this->data[$sec_key][$id])){
                                            $this->setError($id, 'NOT VALID');
                                        }
                                   }
                             }                       
                       }
                    }
                }// by fields
           } //foreach
        }
        else{ //Validation of Main Settings
               foreach ($this->map as $sec_key=>$section)  // by sections
               { 
                    foreach ($section as $id => $value) { //by fields
                        if(isset($value['required']) && $value['required'] ===true){
                             if(!isset($this->data[$sec_key][$id]) || $this->data[$sec_key][$id]=='' || empty($this->data[$sec_key][$id])){
                                 $this->setError($id, 'Required data.');
                             }
                        }  

                       if(!$this->fieldHasErrors($id)){
                           //Proceede with validation
                           if(isset($this->data[$sec_key][$id]) && $this->data[$sec_key][$id]!='' && !empty($this->data[$sec_key][$id])){
                                 if (isset($value['validators']) && is_array($value['validators'])){
                                       foreach ($value['validators'] as $validator){
                                            if(!$this->{$validator}($this->data[$sec_key][$id])){
                                                $this->setError($id, 'NOT VALID');
                                            }
                                       }
                                 }                       
                           }
                        }
                    }// by fields
               } //foreach
        }         
       
       if($this->hasErrors()){
           return false;
       }
       else{
           return true;
       }
    }
    

    /**
     * Enter description here ...
     * @param unknown_type $filters
     * @param unknown_type $data
     */
    public function applyFilters(){
        foreach ($this->map as $sec_key => $section)  // by sections
        {
            foreach ($section as $id => $value) {
               //Proccede with validation
               if(isset($this->data[$sec_key][$id]) && $this->data[$sec_key][$id]!='' && !empty($this->data[$sec_key][$id])){ //data set
                     if (isset($value['filters']) && is_array($value['filters'])){ //filter set
                       foreach ($value['filters'] as $filter){
                           $this->_applyFilter($this->data[$sec_key][$id], $filter);
                       }
                     }                       
               }
           } //foreach
        }   
    }
    
   /**
    * Enter description here ...
    * @param mixed $el
    * @param string $filter
    */
    public function _applyFilter(&$el, $filter=null)
    {
        if(method_exists($this, $filter)){
            if(is_array($el) && !empty($el)){ 
                foreach ($el as $k => $v){
                     $el[$k] = $this->_applyFilter($v, $filter);
                }
            }else {
                return $this->{$filter}($el);
            }
        }else{
            throw new Exception("Filter does not exists", 1);
        }
       /// return $el;
    }
    
    /**
     * This filters are applied to all data
     * @param unknown_type $fil
     */
    public function addFilter($fil){
        $this->filters[$fil] = $fil;
    }
    
    /**
     *  * This filters are applied to all data
     * Enter description here ...
     * @param unknown_type $fil
     */
    public function removeFilter($fil){
        if(isset($this->filters[$fil])){
            unset($this->filetes[$fil]);
        }
    }
    
    /**
     * Return an error message for particulat fieldn if any
     * @param string $id
     */
    public function fieldHasErrors($id){
        return (isset($this->errors[$id])) ? $this->errors[$id] : false;
    }
    
    public function hasErrors(){
        return !empty($this->errors);
    }

    public function toString(){
        $html = '';

        if($this->hasErrors()){
             foreach ($this->errors as $key => $value) {
                $html += "<li>$value</li>";
            }
          $html = '<ul>'.$html.'</ul>';
        }
        else{
            $html = '<p>No Errors</p>';
        }
        return $html;
    }

    protected function setError($id, $error){
        $this->errors[$id] = $error;
    }
    
    /*
     * Vraca ID polja u kom je doslo do greske 
     */
    public function getErrors(){
        return $this->errors;
    }
    
    /* From this point validators */
    
    protected function _email($email){
        return is_email($email);
    }
    
    protected function _alfa($param){ return true; }
    protected function _alfaNumeric($param){
        return true;
    }
    protected function _int($param){
        return is_int($param);
    }
    protected function _numeric($param){
        return is_numeric($param);
    }
    protected function _float($param){
        return is_float($param);
    }
    
    protected function _noNull($param){
        return (isset($param) && $param != '') ? true : false;
    }
    
    protected function _url($param){
        $regex = '/(http|ftp|https):\/\/[\w\-_]+((\.[\w\-_]+)+)([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?/';
        return (preg_match($regex, $param) > 0) ? true : false;
    }
    
    protected function _phone($param){
        return true;
    }

    /* Sanitarizers */
    
    
    protected function strip_tags($param){
        return strip_tags($param);
    }
    
    protected function intVal($param){
        return (int) $param;
    }


}