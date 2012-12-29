<?php
/**
 * Helper functions
 */

/**
 * 
 * Enter description here ...
 * @param unknown_type $data
 * @param unknown_type $echo
 */
function mls_renderSlider($data = array(), $echo = true){
    
    $scroller_source_type = $scroller_source = null;
    
    if(defined('FORCE_FULL_SLIDER') && FORCE_FULL_SLIDER)
    {
        $show_post_scroller = false;
    }
    else{
        $show_post_scroller = Data()->isOn('mi_home.scroller_switch');
    }
    
    if($show_post_scroller){
        if($scroller_source_type == null){
                $scroller_source_type = Data()->getMain('mi_home.scroller_resource_switch');    
        }
            
        if($scroller_source == null){
            if($scroller_source_type == 'spost'){
                $scroller_source = Data()->getMain('mi_home.scroller_category');
            }else if ($scroller_source_type == 'spage'){
                $scroller_source = Data()->getMain('mi_home.scroller_pages');
            }
        }
    }
    
    $output = '';
    
    if(!empty($data)){ 

        if(defined('FORCE_PIECEMAKER_SLIDER') && FORCE_PIECEMAKER_SLIDER){
            $slider_type = 'piece_slider';
        }else{
            $slider_type = $data['current_slider'];
        }

        $settings = $data['data'][$slider_type]['settings'];
        $slides   = $data['data'][$slider_type]['item_data'];  

        switch ($slider_type){
            case 'cycle_slider':{ 
                $slider = new CycleSlider($scroller_source, array(), $scroller_source_type, $settings, $slides);
                break;
            }
            case 'piece_slider':{ 
                 $slider = new PieceMakerSlider($settings, $slides);
                break;
            }
        }
        if(isset($slider)){
             $output = $slider->render();
        }
    }
    
    if($echo){
        echo $output;
    }
}

/**
 * 
 * Enter description here ...
 * @param unknown_type $str
 * @param unknown_type $wordsreturned
 */
function mls_abstract($str = '', $wordsreturned = 20, $echo=false, $not_strip = array('<p>')){
    //Strip all tags
    $str = strip_tags($str, implode('', $not_strip));
    $retval = $str;
    $array = explode(' ', $str);
    if (count($array) <= $wordsreturned) {
        $retval = $str;
    }
    else {
        array_splice($array, $wordsreturned);
        $retval = implode(' ', $array).'...';
    }
    
    if($echo){
        echo $retval;
    }else{
        return $retval;
    }
}


function mls_the_excerpt_max_charlength($excerpt, $charlength) {
	$charlength++;
    $retval ='';
    
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = mb_split( ' ', $subex );
		
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		
		$retval .=  $subex;
		$retval .= '...';
	} else {
		$retval .= $excerpt;
	}
	return $retval;
}

/**
 * 
 * Enter description here ...
 * @param unknown_type $options
 * @param unknown_type $selected
 * @param unknown_type $attribs
 * @param unknown_type $echo
 */
function mls_RenderSelectBox($options, $current=null, $attribs = array('class'=>'', 'id'=>'', 'name'=>'', 'multi'=>false), $echo = false){
    
    $output = '';
    $options_str = '';
    
    foreach ($options as $k=>$v){
        if(is_array($selected)){
            $selected = (in_array($k, $current)) ? 'selected' : '';
        }
        else{ // echo $k . '==' . $current."\n\n";
            $selected = ($k == $current) ? 'selected' : '';
        }
        $options_str .= '<option value="'.esc_attr($k).'" '.$selected.' >'.esc_attr($v).'</option>';
    }
    
    $output = '<select ' .
        			($attribs['name']  ? 'name="'.$attribs['name'].'"' : 'name="select_box"') .'"' . 
        			($attribs['id']    ? 'id="'.$attribs['id'].'"' : 'id="select_box_id"') . 
        			($attribs['multi'] ? 'multiply="multiply"'     : '') .
        			($attribs['class'] ? 'class="'.$attribs['class'].'"' : '') .
                 	' >' 
        			. $options_str . 
    		  '</select>';
    if($echo){
        echo $output;
    }else{
        return $output;
    }
}