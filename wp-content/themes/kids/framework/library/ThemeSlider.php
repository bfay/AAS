<?php
abstract class ThemeSlider
{
    /**
     * Slider Type
     * @var unknown_type
     */
    public $type = '';

    /**
     * Slider name
     * @var unknown_type
     */
    public $name = '';

    /**
     * All settings related to slider, loaded form DB
     * @var unknown_type
     */
    protected $settings = '';

    /**
     * Array of slides
     * @var unknown_type
     */
    protected $slides = array();

    /**
     * Category id of scroller from the right
     * @var unknown_type
     */
    public $category_id = null;

    /**
     * Array of posts related to a scroller from the right
     * @var unknown_type
     */
    protected $posts = array();

    /**
     * Rendered output for scroller
     * @var unknown_type
     */
    public $post_output = '';

    /**
     * Renedered output for slider witout scroller
     * Enter description here ...
     * @var unknown_type
     */
    public $slider_output = '';

    /**
     * Handle related to a JS file for slider
     * Enter description here ...
     * @var unknown_type
     */
    protected $js_handle = '';
    
    /**
     * Name of the CSS class related to layout. If scroller turn on => "c-8" otherwise "c-12"
     * Enter description here ...
     * @var unknown_type
     */    
    protected $slider_container_class = 'c-8'; // 2/3 width
    
    
     /**
     * Render SLider
     * @param boolean $echo
     */
    abstract public function _renderSlider ();
    /**
     * Enqueue all scripts and CSS related to a slider and scroller. Override it in sub class.
     * @throws Exception
     */
    abstract public function enqueueScripts();
    
    
    /**
     * 
     * Enter description here ...
     * @param int $category_id post category
     * @param array $posts   array of post objects
     * @param array $settings  slider settings
     * @param array $slides slide settings
     * @param boolean $force_reload force post reloading
     */
    public function __construct ($scroller_source = null, $posts = array(), $scroller_source_type=null, $settings, $slides)
    {
        $this->scroller_source_type = $scroller_source_type;
        $this->scroller_source       = $scroller_source;
        $this->settings    = $settings;
        $this->slides      = $slides;
        
        if (!empty($posts)) {
            $this->posts = $posts;
        }elseif(empty($posts) && $scroller_source!=null){
            if($this->scroller_source_type == 'spost'){ 
                $this->_loadPosts();
            }else if ($this->scroller_source_type == 'spage'){
                $this->_loadPages();
            }
        }else{
            $this->slider_container_class = 'c-12'; //Full width
        }
    }
    
    /**
     * Render shole thing
     */
    public function render(){
        $output = '';
        $output = $this->_renderSlider();
        if ($this->_hasCatScroller()) {
            $output .= $this->_renderCategoryScroller();
        }
        return '<div id="slider" class="'.$this->type.'"><div class="wrap">' . $output . '</div></div>';
    }
    
    
    protected function _renderCategoryScroller ()
    {
        $per_page = Data()->getMain('mi_home.scroller_items_num');
        $o = '';
        if ($this->_hasPosts()) {
            $n = (int) ceil(count($this->posts) / $per_page);
            
            for ($i = 0; $i < $n; $i ++) { //"Goes by pages - Paginations"
                $o .= '<ul>';
                for ($k = $i * $per_page; $k < $i * $per_page + $per_page; $k ++) {
                    
                    if(!isset($this->posts[$k])) break;
                    if(strlen($this->posts[$k]->post_title) > 40)$this->posts[$k]->post_title =  substr($this->posts[$k]->post_title, 0, 38) . '...';
                    
                    $o .= '<li><h3><a href="' . get_permalink($this->posts[$k]->ID) . 
                                   '" title="' . $this->posts[$k]->post_title . '">' .
                                                 $this->posts[$k]->post_title . '</a></h3>
                                                 '.the_subtitle($this->posts[$k]->ID) .'
                                                 </li>'."\n";
                }
                $o .= '</ul>'."\n";
            }
        }
        
        $this->post_output = '<div class="c-4">
                <div id="cat-slider">
                    <h2>' . Data()->getMain('mi_home.scroller_title') . '</h2>
                    <span class="devider"></span>
                    <div id="scroller">
           				' . $o . '                
                    </div><!-- END scroller -->
                    
                    <span class="up-arrow" title="Next"></span>
                    <span class="down-arrow" title="Previous"></span>
                    
            	</div> <!-- end cat-slider -->
            </div>';
        return $this->post_output;
    }
    
    /**
     * Does scroller exists
     */
    protected function _hasCatScroller ()
    {
        return ($this->scroller_source === null) ? false : true;
    }

    /**
     * Do we have posts loaded?
     */
    protected function _hasPosts ()
    {
        return count($this->posts) > 0;
    }

    /**
     * Loads post from specified category. Posts realted to a scroller.
     */
    protected function _loadPosts ()
    {
        $posts = get_posts(array('numberposts' => 999, 'category' => $this->scroller_source));
        $this->posts = $posts;
        return $posts;
    }
    
    /*Load Specifiend pages */
    protected function _loadPages(){
         $pages = get_pages(array('include'=>$this->scroller_source, 'hierarchical'=>0,'post_status'=>'publish'));
         $this->posts = $pages;
         return $pages;
    }
} // End of class