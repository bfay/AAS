<?php
/**
 *   XML file generator for piece-maker
 */ 

require_once( '../../../../../../wp-load.php' );

$op = Data();//new ThemeOptions('kids_options');
$data = $op->getSliderOpts();

$transitions = $data['data']['piece_slider']['transition'];
$settings = $data['data']['piece_slider']['settings'];
$slides   = $data['data']['piece_slider']['item_data'];

$image_suffix_size = '-945x300';

/* Slider settings*/


$settings_str = '<Settings ImageWidth="945" ImageHeight="300"';
foreach ($settings as $key => $value){
   $settings_str .= ' ' . $key . '="'.str_replace('#', '0x', $value).'"'; 
}
$settings_str .= '></Settings>'."\n";

$slides_str = '';



foreach ($slides as $slide) {
    
    $file_info = pathinfo($slide['slide_url']);
    
    if($file_info['extension'] == 'swf'){
        $slides_str .= '<Flash Source="'.$slide['slide_url'].'" Title="'.$slide['slide_name'].'">
                          <Image Source="'.$slide['slide_thumbswfimage'].'" />
                        </Flash>';
        
    }elseif($file_info['extension'] == 'mpg' || $file_info['extension'] == 'mpeg' || $file_info['extension']=='mp4' ){
        $slides_str .= '<Video Source="'.$slide['slide_url'].'" Title="'.$slide['slide_name'].'" Width="950" Height="300" Autoplay="true">';
        $slides_str .= '<Image Source="'.$slide['slide_thumbmpegimage'].'" />';
        $slides_str .= '</Video>';
    }
    else{ 
        
        // = $slide['slide_url'];
        $tmp_name = preg_replace("/(.+)[\.]{1}(jpg|png|gif|jpeg){1}$/i", '$1'. $image_suffix_size . '.$2', $slide['slide_url']);
        //Suppose it is an image.
        //var_dump($tmp_name);
        $pos = strpos( $tmp_name, 'wp-content');
        $path = substr($tmp_name, $pos );
        
        $path = ABSPATH . $path;
        
        if(file_exists($path)){
            $slide['slide_url'] = $tmp_name;
        }
        
        $slides_str .= '<Image Source="'.$slide['slide_url'].'" Title="'.$slide['slide_name'].'">';
        if(isset($slide['slide_desc']) && $slide['slide_desc']!='')
             $slides_str .= '<Text>' . htmlspecialchars(stripslashes($slide['slide_desc'])) . '</Text>'; 
        if(isset($slide['slide_link']) && $slide['slide_link']!='')
             $slides_str .= '<Hyperlink URL="'.$slide['slide_link'].'" Target="_blank" />';
        $slides_str .=  '</Image>';
    }
}

$tr_str = '<Transitions>';
//foreach ($transitions as $tr){
    $tr_str .= '<Transition Pieces="'.$transitions['Pieces'].'" Time="'.$transitions['Time'].'" Transition="'.$transitions['Transition'].'" Delay="'.$transitions['Delay'].'" DepthOffset="'.$transitions['DepthOffset'].'" CubeDistance="'.$transitions['CubeDistance'].'"></Transition>';
//}
$tr_str .= '</Transitions>';

$content = '<Contents>' .$slides_str . '</Contents>';

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>';
echo '<Piecemaker>';
echo $content;
echo $settings_str;
echo $tr_str;
echo '</Piecemaker>';