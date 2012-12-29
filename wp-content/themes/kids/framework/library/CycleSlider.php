<?php
class CycleSlider extends ThemeSlider
{
    /**
     * This settings can be changed by user
     * @var unknown_type
     */
    protected $run_settings_index = array('autostop', 'autostopCount', 'delay', 'fx', 'pause', 'speed', 'timeout');
    
    public function __construct ($scroller_source = null, $posts = array(), $scroller_source_type=null,$settings=array(), $slides=array())
    {
        $this->type = 'cycle_slider';
        $this->js_handle = ThemeSetup::HOOK . '_jquery_cycle_all_js';

        parent::__construct($scroller_source, $posts, $scroller_source_type, $settings, $slides);
         //Hooks And Scripts
        $this->enqueueScripts();
    }
    
    
    public function enqueueScripts ()
    {        
        $p = array();
        foreach ($this->run_settings_index as $index) {
            $p[$index] = $this->settings[$index];
        }
        
        $p = array(
            'slider_type'=> $this->type,
            'opts'=> array('autostop'     => ($this->settings['autostop'] == 'on') ? 1 : 0, 
                           'autostopCount'=> (int) $this->settings['autostopCount'], 
                            'delay'       => (int)  $this->settings['delay'], 
                            'fx'          => $this->settings['fx'], 
                            'pause'       => ($this->settings['pause'] == 'on') ? 1 : 0, 
                            'speed'       => (int) $this->settings['speed'], 
                            'timeout'     => (int) $this->settings['timeout'],
                            'easing'      => $this->settings['easing'])
        );
        
        wp_enqueue_script($this->js_handle, KIDS_JS_URI . '/jquery.cycle.all.js', array('jquery', ThemeSetup::HOOK . 'jquery-easing-js'));
        wp_enqueue_script($this->js_handle . '_sliders', KIDS_JS_URI . '/sliders.js', array('jquery'));    
        wp_localize_script($this->js_handle . '_sliders', ThemeSetup::HOOK.'Sliders', $p);
    }
    
    /**
     * Render Slider staff
     * @see ThemeSlider::_renderSlider()
     */
    public function _renderSlider ()
    {
        $output = '';
        
        if($this->slider_container_class == 'c-12'){ // Full width slider
            $image_suffix_size = '-945x300';
        }
        else{
            $image_suffix_size = '-650x300';
        }
        
        foreach ($this->slides as $i => $s) {
            $slide_url = $s['slide_url'];
            $s['slide_url'] = preg_replace("/(.+)[\.]{1}(jpg|png|gif|jpeg){1}$/i", '$1'. $image_suffix_size . '.$2', $s['slide_url']);
            $pos = strpos( $s['slide_url'], 'wp-content');
            $path = substr($s['slide_url'], $pos );
            
            $path = ABSPATH . $path;

            if(!file_exists($path)){ 
                $s['slide_url'] = $slide_url;
            }
           
            $output .= '<li>
                           <a href="'.$s['slide_link'].'"><img src="'.esc_attr($s['slide_url']).'" alt="'.esc_attr($s['slide_name']).'" title="'.esc_attr($s['slide_name']).'" /></a>
                        </li>';
        }
        
        //Setup all option for slider
        $output = '<div class="'.$this->slider_container_class.'">
            	<div id="big-slider">
                    <div class="slider-mask"></div>
                    <ul id="slider-items">' . $output . '</ul>';
                        
        if($this->settings['has_pagination'] == 'on'){
            $output .= '  <!-- This list is populated by jQuery Cycle -->
                    <div id="pagination-container">
                        <span class="previous"></span>
                        <span id="slider-pagination"></span>
                        <span class="next"></span>
                    </div>';
        }                
                  
        $output .= '</div><!-- END big-slider -->
            </div> <!-- end c-8 -->
            <div id="slider-bg"></div>';
        
        return $output;
    }
    
    
    protected function buildOptions ()
    {
        $opts = array();
        foreach ($this->settings as $k=>$v) {
            
        }
        wp_localize_script($this->js_handle, 'sliderOpts', $this->settings);
    }
} //CycleSlider Class End