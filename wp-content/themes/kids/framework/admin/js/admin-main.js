/* 
   Main JS for administation panel 
   jQuery plugin for special element text field with a multi select box. Used for sidebars.
*/
(function( $ ) {
  $.fn.mlsCustomList = function(method) {

      /* Plugin Methods */
      var methods = {
          init : function( options ) { 
              return this.each(function() {
                  var $this = $(this);//, 
                  var addButton     = $this.find(".controls a");
                  /* Binding events */
                  addButton.bind('click.mlsCustomList', methods.add);
                  $ul = $this.next().find("li span").on("click.mlsCustomList", $this.next().find("li span"), methods.remove);
              });// Main Return End
          },
          add : function(event) {
              var $this = $(this);
              var text          = $this.parent().find("input");
              var txt = $.trim(text.val());
              
              if(txt != '' && 
                 $this.parent().parent().next().children().filter(function(){ return ($(this).data("sidebarid") == txt)}).length == 0   
              ){
                  var dataContainer = $this.parent().find("select");
                  var ulList        = $this.parent().parent().next();//.find("select");
                  
                  $close  = $("<span />");
                  $a = $("<a />").attr("href","#").html("×").appendTo($close);
                  $li = $("<li />").addClass("accordion-heading").html(txt).data("sidebarid", txt);
                  
                  $close.appendTo($li);
                  ulList.append($li);
                  $close.on("click.mlsCustomList", $close, methods.remove);
                  dataContainer.append('<option value="'+txt+'" selected="selected">'+txt+'</option>');
                  //Clear text field
                  text.val("");
              }
              return false;
          },
          remove : function() { 
              var $this = $(this);
             
              var dataContainerItem = $this.parent().parent().prev().find("select option[value='"+$this.parent().data('sidebarid')+"']");
              dataContainerItem.remove();
              $this.parent().fadeOut(function(){
                 $(this).remove();
              });
              return false;
          },
          update : function( content ) {}
        };
      
      // Method calling logic
      if ( methods[method] ) { //Method exists. Apply it.
          return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
      } else if ( typeof method === 'object' || ! method ) { // is object or not defined. Call Init
          return methods.init.apply( this, arguments );
      } else {
          $.error( 'Method ' +  method + ' does not exist in this plugin!' );
      }    
  };
})( jQuery );


jQuery(document).ready(function(){

    var jquery_version = jQuery.fn.jquery;
    jQuery(".collapse").collapse({"toggle":false});
    //Open First Tab By default
    jQuery('#main_navigation a:first').tab('show');
    jQuery("a[href='#mi_look_feel']").tab('show');

    jQuery("body").on("click", ".btn",function(e){
        e.preventDefault();
    });
    
    /**/
    jQuery(".custom_multi_list").mlsCustomList();
    
    /* Hiding disabled sub sections */
    manageAdminOptions = function(){
        var parents = jQuery(".control-group").filter(function(index){
              return (jQuery(this).data('haschildren') == 1);
        });
        
    jQuery.each(parents, function(index, item){
        
            var $parent       = jQuery(item);
            var $parent_field = $parent.find(".controls>:first-child");
            var val = $parent_field.val();

            //Get direct children for current element
            var children = $parent.parent().children().filter(function(){
                  return (typeof jQuery(this).data("parentid") != 'undefined' && jQuery(this).data("parentid") == $parent_field.attr("id"));
            });
            
            /* show children who has class == value of a parent and parent is displayed */
            jQuery.each(children, function(){
                var $item = jQuery(this);
                if($item.hasClass(val) && $parent.css("display")=="block" || ($parent.css("display")=="inline-block")){
                    $item.css("display", "block");
                }else{
                    $item.css("display", "none");
                }
            });

             if($parent_field.attr("type") == "hidden"){
                  
                  $parent.on("click", "a", function(e){ 
                      $this = jQuery(this);
                      var valc = $this.data("state"); 

                      //Get direct children for current element
                      var children =  $parent.parent().children().filter(function(){
                            return (jQuery(this).data("parentid") == $this.parent().parent().find("input[type='hidden']").attr("id")) 
                      });
                      
                      jQuery.each(children, function(){
                          var $item = jQuery(this);
                          var valcx = $item.find(".controls>:first-child").val();
                        //  alert(valcx);
                          if($item.hasClass(valc) && ($this.css("display")=="block" || $this.css("display")=="inline-block")){ 
                            //alert("HAS CLASS"+valc+" BLOCK");
                              $item.fadeIn("slow");
                               var subchildren = $item.parent().children().filter(function(){
                                    return (jQuery(this).data("parentid") == $item.find(".controls>:first-child").attr("id"));
                              });

                               jQuery.each(subchildren, function(){
                                   var $sItem = jQuery(this);
                                   if($sItem.hasClass(valcx) && ($item.css("display")=="block" || $item.css("display")=="inline-block")){
                                        $sItem.fadeIn("slow");
                                   }
                               });
                          }else{
                              $item.css("display", "none");//fadeOut("fast");
                               var subchildren = $item.parent().children().filter(function(){
                                    return ((jQuery(this).data("parentid") == $item.find(".controls>:first-child").attr("id")));
                              });
                               
                               jQuery.each(subchildren, function(){
                                   var $sItem = jQuery(this);
                                        $sItem.fadeOut("fast");
                               });
                          }
                    });
                    e.preventDefault();
                 });
            } else{
                //selectbox
            }
        }); // end foreach
    }; // end of function 

    
    /* Add modal box HTML to document body. IE fix */

     modal_str = '<div class="modal fade" id="myModal" data-backdrop="static">\
            <div class="modal-header">\
                <a class="close" data-dismiss="modal">×</a>\
                <h3>Operation in progress</h3>\
            </div>\
            <div class="modal-body">\
                <p>Saving in progress...</p>\
                <img src="../wp-content/themes/kids/framework/admin/images/ajax-loader.gif" />\
            </div>\
            <div class="modal-footer">\
            </div>\
        </div>';

    mls_modal_box = jQuery(modal_str);
    jQuery(mls_modal_box).appendTo(document.body);

    var viewFormfield = '';
    var viewSpanfield = '';
    
    window.original_send_to_editor = window.send_to_editor;
    
    if(!compareVersion('1.7.0', jquery_version)){
        jQuery('body').delegate(".slide-uploader, .uploader", "click", function() {
            $this = jQuery(this);
            
             viewFormfield = jQuery(this).parent().find("input[type='hidden']");
             viewSpanfield = jQuery(this).parent().find(".filename");
             tb_show('', 'media-upload.php?type=image&post_id=&TB_iframe=true');
             //Insert in to post
             window.send_to_editor = function(html) {
                 if($this.hasClass("uploader")){
                     imgurl = jQuery('img', html).attr('src');
                 }
                 else{
                     imgurl = jQuery(html).attr('href');
                 }
                  //Set hidden field value
                  jQuery(viewFormfield).val(imgurl);
                  jQuery(viewSpanfield).html(imgurl);
                  tb_remove(); 
                  window.send_to_editor = window.original_send_to_editor;
             };
             return false;
        });

        jQuery("body").delegate(".remove-slide", "click", function(e){
            jQuery(this).parent().parent().fadeOut(function(){
                jQuery(this).remove();
            });
        });
         jQuery('body').delegate('[data-toggle=buttons-radio] a','click',  function ( e ) {
              jQuery(this).parent().parent().find("input[type='hidden']").val(jQuery(this).attr("data-state"));
        });
    }else{
        /* Customization */
        jQuery('body').on("click", ".slide-uploader, .uploader", function() {
            
            $this = jQuery(this);
            viewFormfield = jQuery(this).parent().find("input[type='hidden']");
            viewSpanfield = jQuery(this).parent().find(".filename");
            tb_show('', 'media-upload.php?type=image&post_id=&TB_iframe=true'); 
            
            //Insert in to post
            window.send_to_editor = function(html) {
                if($this.hasClass("uploader")){
                    imgurl = jQuery('img', html).attr('src');
                }
                else{
                    imgurl = jQuery(html).attr('href');
                }
                 //Set hidden field value
                 jQuery(viewFormfield).val(imgurl);
                 jQuery(viewSpanfield).html(imgurl);
                 tb_remove(); 
                 window.send_to_editor = window.original_send_to_editor;
            };
            return false;
        });
        
        jQuery("body").on("click", ".remove-slide", function(e){
            jQuery(this).parent().parent().fadeOut(function(){
                jQuery(this).remove();
            });
        });
         jQuery('body').on('click', '[data-toggle=buttons-radio] a', function ( e ) { 
              jQuery(this).parent().parent().find("input[type='hidden']").val(jQuery(this).attr("data-state"));
        });
    }     
    

    /* ========================== Loading data ============================ */
    
    
    /* Load data required for sliders */
    var slider_data = LoadSliderSettings();
    /* Current slider */
    var sliderObj = {};
    /* Array of created sliders */
    var slidersContainer = {};

    if(typeof slider_data.current_slider != "undefined"){
        if (slider_data.current_slider == 'cycle_slider') {
            sliderObj = new CycleSliderClass('Cycle Slider', slider_data);
        }else if (slider_data.current_slider == 'piece_slider') {
            sliderObj = new PieceSliderClass('PieceMaker Slider', slider_data);
        }else{
            sliderObj = new CycleSliderClass('Cycle Slider',slider_data);
        }
        sliderObj.new_slider = false;
    }else{ //By default
        sliderObj = new CycleSliderClass('Cycle Slider',slider_data);
    }

    sliderObj.initSlider();
    sliderObj.buildIncommonSettings();
    sliderObj.buildSliderSettings();
    
    slidersContainer[slider_data.current_slider] = sliderObj;
    
    jQuery(".number").mlsNumberSlider();
    jQuery(".mls_colorpicker").ColorPicker({
        
        onSubmit: function(hsb, hex, rgb, el) {
            jQuery(el).css("background-color", "#"+hex);
            jQuery(el).val("#"+hex);
            jQuery(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            jQuery(this).ColorPickerSetColor(this.value);
        }
        })
        .bind('keyup', function(){
            jQuery(this).ColorPickerSetColor(this.value);
        });

    /* On Change Slider Type, remove current Slider and build new one */
    //If Slider type changed. ReBuild whole slider. Ovo je bilo u Build Slider ili settings Metodi!!!!
        function onChangeSliderType(event){
            oldSliderObj = event.data; // Current Slider object
            
            slider_type = jQuery(this).val();
            if(slider_type){
                oldSliderObj.CleanMyStaff();
                oldSliderObj = null;

                //unbind all staff
                jQuery(".add-new-slide").unbind("click");
                
                tmp_data = {};
                /* data for this slider is defined? */
                slider_data = LoadSliderSettings();
                
                if(typeof slidersContainer[slider_type]!= "undefined"){
                    sliderObj = slidersContainer[slider_type];
                     sliderObj.settings = slider_data; 
                }else{
                    if(slider_type == 'cycle_slider') {
                        sliderObj = new CycleSliderClass('Cycle Slider', slider_data); // By default
                    }else if (slider_type == 'piece_slider'){ 
                        sliderObj = new PieceSliderClass('PieceMaker Slider', slider_data); // By default
                    }
                }

                
                sliderObj.initSlider();
                sliderObj.buildIncommonSettings();
                sliderObj.buildSliderSettings();

                slidersContainer[slider_type] = sliderObj;
                
                jQuery(".number").mlsNumberSlider();
                jQuery(".mls_colorpicker").ColorPicker({
                                                onShow: function (colpkr) {
                                                    jQuery(colpkr).fadeIn(500);
                                                    return false;
                                                },
                                                onHide: function (colpkr) {
                                                    jQuery(colpkr).fadeOut(500);
                                                    return false;
                                                },
                                                onSubmit: function(hsb, hex, rgb, el) {
                                                    jQuery(el).val("#"+hex);
                                                    jQuery(el).css('background-color', "#"+hex);
                                                    jQuery(el).ColorPickerHide();
                                                    },
                                                    onBeforeShow: function () {
                                                        jQuery(this).css('background-color', jQuery(this).val());
                                                        jQuery(this).ColorPickerSetColor(this.value);
                                                    }
                                                })
                                                .bind('keyup', function(){
                                                    jQuery(this).ColorPickerSetColor(this.value);
                                                    jQuery(this).css('background-color', jQuery(this).val());
                                                });
            }
        }

        if(compareVersion('1.7.0', jquery_version)){
            jQuery("body").on("change", "select[name$='incommon__slider_type']", sliderObj, onChangeSliderType );
        }
        else{
            jQuery("body").delegate("select[name$='incommon__slider_type']", "change", sliderObj, onChangeSliderType );
        }
        
        /* Parent/Children options */
        manageAdminOptions();
}); // Document Ready


/**
 * Loads slider settings from server. PHP/AJAX
 */
function LoadSliderSettings(){
    
    var resp = {};
    jQuery.ajax({
                  type: 'POST',
                  dataType: 'json',
                  url: kids_ajax.ajaxurl,
                  async: false,
                  data: {'action':'ajaxCallBack', 'sec_action':'load_sliders','kids_admin_ajax_nonce':kids_ajax.kids_admin_ajax_nonce},
                  success: function(data){
                      resp = data;
                  },
                  error: function(response){
                      resp = response;
                  }
                });
    return resp;
}


/* From this point only Classes - SLIDERS - */
/* Super class of all Sliders */

function SuperSliderClass(name, settings, id){
    
    //Self reference / private attribute
    var self = this;
    
    /* Slider name */
    this.name = name;
    
    /* Slider type*/
    this.type = '';
    
    /* Multi dimensional array */
    this.settings = settings;
    
    //Unique slider ID
    this.slider_id = null;//id;

    this.settings_tpl = null;//settings.tpls;

    this.new_slider = true;

    this.new_slide  = true;

    this.numOfSlides = 0;
    
    /* Methods */
    
    /**
     *  Slider initialization.
     */
    this.initSlider = function(){

        this.settings_tpl = this.settings.tpls;

        this.settings.tpls = null;

        delete this.settings.tpls;

        if(this.slider_id == null){
            this.slider_id = this.generateSliderId(); //this.type
        }else{
            this.new_slider = false;
        }

       // this.numOfSlides = jQuery("#slides_container .accordion-group").length;
       this.calculateNumberOfSlides();

        /* Add new Slide button */
        jQuery(".add-new-slide").click(function(e) 
        {
            self.addNewSlide();
            self.afterSlidesInDom();
            e.preventDefault();
        });

        /* Save slider button pressed, Real saving is handeled from admin-ajax.js. This piece of code just updates data structures */
       // jQuery(".save-button-action").click(function(){
       //     jsonSlidersDataTmp = jQuery("#slider_form").serializeSlidersJSON();
       // });
    }
    
    //Count existing and return next num.
    this.generateSliderId = function(){
        var i = 1 + jQuery(".slider_form").length;
        return this.type+ "_" + i;
    }

    this.calculateNumberOfSlides = function(){
      if(typeof this.settings[this.type] != "undefined" && typeof this.settings[this.type]['item_data'] != "undefined"){
        this.numOfSlides = this.settings[this.type]['item_data'].length;
      }else{
        this.numOfSlides = 0;
      }

    }
    this.getNumberOfSlides = function(){
      return this.numOfSlides;
    }

    this.buildIncommonSettings = function (){
        settings = '';
        if(this.new_slider == true && jQuery.isEmptyObject(this.settings[this.type])){ 
            settings = this.getSettingsTpl();
        }
        else{
            settings = this.getSettings();
        }

        settings[this.type]['incommon']['slider_type']['default'] = this.type;
        var output = '';
        for(var item in settings[this.type]['incommon']){ 
            output += this.parseField(settings[this.type]['incommon'][item], this.type+"__incommon");
        }
        jQuery(".slider_incommon_point").after(output);
    }

    this.afterSlidesInDom = function(){
        alert("DO IT IN SubClass");
    }

    /**
     * According to a field type and other data create adequate HTML element
     */
    this.parseField = function(field, section){ 
        var output = '';
        var value = (field['value'] == undefined) ? field['default'] : field['value'];
        if ( (typeof field['children'] != 'undefined') && (field['children'] == true)) {
            has_children = 'data-haschildren="1"'; 
        } else {
            has_children = 'data-haschildren="0"';
        }
        
        depend    = (typeof field['depend'] != 'undefined') ? field['depend'] : '';
        depend_id = (typeof field['depend_id'] != 'undefined') ? field['depend_id'] : '';
        parent_id = (typeof field['parent_id'] != 'undefined') ? 'data-parentid="'+field['parent_id']+'"' : '';
        
        switch(field['type'])
        {
            case 'text':{
                output += '<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                                <label class="control-label">'+field['name']+'<br/></label>\
                                <div class="controls">\
                                    <input id="'+section+'__'+field['id']+'" type="text" name="'+section+'__'+field['id']+'" value="'+value+'" class="span8" />\
                                    <p class="help-block">'+field['desc']+'</p>\
                                </div>\
                            </div>';
              break;
            }
            case 'textarea':{
                     output += '<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                                    <label class="control-label">'+field['name']+'</label>\
                                    <div class="controls">\
                                        <textarea id="'+section+'__'+field['id']+'" rows="4" name="'+section+'__'+field['id']+'" class="span8">'+value+'</textarea>\
                                        <p class="help-block">'+field['desc']+'</p>\
                                    </div>\
                                </div>';
                    break;
            }
           case 'number' : {
                output += '<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                    <label class="control-label">'+field['name']+'</label>\
                    <div class="controls">\
                    <input id="'+section+'__'+field['id']+'" readonly="readonly" type="text" name="'+section+'__'+field['id']+'" value="'+value+'" class="span2" style="float:left;" />\
                    <div class="number span10" data-step="'+field['step']+'" data-min="'+field['min']+'" data-max="'+field['max']+'"></div>\
                        <p class="help-block">'+field['desc']+'</p>\
                    </div></div>';
                break;
            }
          case 'checkbox':{
                 output += '<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                                <label class=" control-label">'+field['name']+'<br/></label>\
                                <div class="controls">\
                                    <input id="'+section+'__'+field['id']+'" name="'+section+'__'+field['id']+'" type="hidden" value="'+value+'" />\
                                    <div data-toggle="buttons-radio" class="btn-group">\
                                        <a href="#" data-state="on" class="btn '+((value == 'on')?  'active':'')+'">ENABLED</a>\
                                        <a href="#" data-state="off" class="btn '+((value == 'off')? 'active':'')+'">DISABLED</a>\
                                    </div>\
                                    <p class="help-block">'+field['desc']+'</p>\
                                </div></div>';
              break;
          }
           
          case 'radio': {
              options = field['values'];
              options_str = '';
              
              for (var k in options){
                  selected = (k == value) ? 'active' : '';
                  //options_str += '<option value="'+k+'" '+selected+' >'+options[k]+'</option>';
                  options_str += '<a href="#" data-state="'+k+'" class="btn '+selected+'" >'+options[k]+'</a>';
              }
              
              output +='<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                            <label class=" control-label">'+field['name']+'<br/></label>\
                              <div class="controls">\
                                  <input id="'+ section+'__'+field['id']+'" name="'+section+'__'+ field['id']+'" type="hidden" value="'+value+'" id="'+field['id']+'"  />\
                                  <div data-toggle="buttons-radio" class="btn-group">'+options_str+'</div>\
                                  <p class="help-block">'+field['desc']+'</p>\
                              </div>\
                         </div>';
              break;
          }
          
            case 'file':{
                   if (value == '') {
                        filename = "No file...";
                   } else{
                        filename = value;
                   }
                   output += '<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                                    <label class="control-label">'+field['name']+'<br/></label>\
                                    <div class="controls">\
                                        <input id="'+section+'__'+field['id']+'" type="hidden" name="'+section+'__'+field['id']+'" value="'+value+'" />\
                                        <div class="btn-group slide-uploader">\
                                            <a href="#" name="f_'+field['id']+'" class="btn active filename span8">'+filename+'</a>\
                                            <a href="#" class="btn action">Upload File</a>\
                                        </div>\
                                        <p class="help-block">'+field['desc']+'</p>\
                                    </div></div>';      
                    break;
          }
          case 'select':{
                options = field['values'];
                options_str = '';

                for (var k in options){
                    selected = (k == value) ? 'selected' : '';
                    options_str += '<option value="'+k+'" '+selected+' >'+options[k]+'</option>';
                }

                 output +='<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                    <label class="control-label">'+field['name']+'</label>\
                    <div class="controls">\
                        <select id="'+section+'__'+field['id']+'" name="'+section+'__'+field['id']+'">\
                            '+options_str+'\
                        </select>\
                        <p class="help-block">'+field['desc']+'</p>\
                    </div></div>';         
                break;
          }
          case 'multiselect':{
                options = field['values'];
                options_str = '';
                for (var k in options){
                    selected = (k == value) ? 'selected' : '';
                    options_str += '<option value="'+k+'" '+selected+' >'+options[k]+'</option>';
                }
                output +='<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                            <label class="control-label">'+field['name']+'</label>\
                            <div class="controls">\
                                <select id="'+section+'__'+field['id']+'" name="'+section+'__'+field['id']+'" multiple="multiple">\
                                    '+options_str+'\
                                </select>\
                                <p class="help-block">'+field['desc']+'</p>\
                            </div></div>';         
                    break;
          }
          case 'colorpicker': 
              
              output += '<div class="control-group dinamic-field '+depend_id+'" '+has_children+' '+parent_id+'>\
                              <label class="control-label">'+field['name']+'<br/></label>\
                              <div class="controls ">\
                                  <input style="background-color:'+value+'" id="'+section+'__'+field['id']+'" type="text" name="'+section+'__'+field['id']+'" value="'+value+'" class="mls_colorpicker" />\
                                  <span class="add-on"><i></i></span>\
                                  <p class="help-block">'+field['desc']+'</p>\
                              </div>\
                          </div>';
              break;
              
          case 'start_sub_section':{
              output += '<div class="control-group dinamic-field theme-section well-no-shadow">\
                        <label class="control-label">'+field['name']+'</label>\
                        </div>';
                        
              break;
          }    
          case 'hidden':{
                output = '<input id="'+section+'__'+field['id']+'" type="hidden" name="'+section+'__'+field['id']+'" value="'+field['value']+'" class="dinamic-field" />';
            break;
          }
         }// End of switch 
         return output;
    }

    //Remove All fields associated with this slider
    this.CleanMyStaff = function(){
        //Remove Old elements
        jQuery("#slider_form .dinamic-field").add("#slides_container >*").fadeOut("slow", function(){
            jQuery(this).remove();
        });
    }

    /**
     * [generateSliderSettings description]
     * @param  {[type]} section incommon, slider_type, slider_settings or item_data :)
     * @return string
     */
    this.buildSliderSettings = function(){
        alert("SHOULD BE DEPRECATED IN SUBCLASS");
    }   
    
    /**
     * Genereate HTML code for a slide of particular slider
     * @return {[type]}
     */
    this.generateNewSlide = function(data){
        var output1 = "";
        var output  = "";
        var tmp = {};
        var slide_data = null;

        this.numOfSlides++;
        if(typeof data != "undefined"){
            slide_data = data;
        } else{ 
            slide_data = this.getSettingsTpl(this.type + "." + "item_data");
        }
        for(var index in slide_data){
                if(slide_data[index].id == 'slide_name') {
                    if(slide_data[index].value){
                         slide_name = slide_data[index]['value'];//.value; 
                    }else{
                         slide_name = slide_data[index]['default'];//xxxtmp['default']; 
                    }
                    slide_name = ((slide_data[index]['value']) ? slide_data[index]['value'] : slide_data[index]['default']);
                }
                jQuery.extend(tmp, slide_data[index]);
                tmp.id = this.numOfSlides+"__"+tmp.id;
                if(typeof tmp.parent_id != "undefined"){
                    tmp.parent_id = this.type+"__item_data__"+this.numOfSlides+"__"+tmp.parent_id;
                }
                
                output1 += this.parseField(tmp, this.type+"__item_data");
                tmp = {};
        }

         output = '<div class="accordion-group">\
                        <div class="accordion-heading">\
                            <a href="#sld'+this.numOfSlides+'" data-parent="#slides_container" data-toggle="collapse" class="accordion-toggle">'+slide_name+'</a>\
                          <a class="close pull-right remove-slide" style="position:relative">&times;</a>\
                        </div>\
                        <div class="accordion-body collapse" id="sld'+this.numOfSlides+'">\
                            <div class="accordion-inner">\
                                <div class="slide">';
        output += output1;
        output += '</div></div></div></div>';
        return output;
    }


    /**
     * Invoked after  user click on Add New Slide button
     */
    this.addNewSlide = function(){
        var slide = this.generateNewSlide();
        jQuery("#slides_container").prepend(slide);
    }
        
    //public
    this.removeSlide = function(id){
        delete this.slides[id];

    }
    
    /* Setters and Getters */
    this.setName = function(name){
        this.name = name;
    }
    
    this.setType = function(type){
        this.type = type;
    }
    
    this.setSettings = function(settings){
        this.settings = settings;
    }
    
    this.setSlides = function(slides){
        //this.slides = slides;
    }
        
    this.getName     = function(){ return this.name; }
    this.getType     = function(){ return this.type; }

    this.getSettings = function(path){ 

        if(path == undefined){
            return this.settings; 
        } else{
            var pointer = '';
            var parts = path.split('.');

            pointer = this.settings;
            for (var i = 0; i < parts.length; i++) {
                pointer = pointer[parts[i]];
            }
            return pointer;
        }
     }

    this.getSettingsTpl = function(path){

        if(path == undefined){
            return this.settings_tpl; 
        } else{
            var pointer = '';
            var parts = path.split('.');

            pointer = this.settings_tpl;
            for (var i = 0; i < parts.length; i++) {
                pointer = pointer[parts[i]];
            }
            return pointer;
        }
    }       
}//End of AbstractSliderClass


/**
 *   ================== [ SUBCLASSES OF SuperSliderClass  ] ======================= 
 */
function CycleSliderClass(name, settings){
    
    var self = this; // Self reference. Use this if we are dealing with calling public methods from private ones.

    /* Call Super Class Constructor with all arguemnts */
    SuperSliderClass.apply(this, arguments);
    
    // Overwrite or define new attribs and methods
    this.type = 'cycle_slider';  
   
    this.afterSlidesInDom = function(){
        
        //When user start to type slide name populate this data at main slide bar
        jQuery("input[name$='slide_name']").keyup(function(event){
            var $this = jQuery(this);
            var title = $this.val().toUpperCase();// + String.fromCharCode(event.keyCode);

            if(title == ''){ title="SLIDE TITLE";}
            $this.parent().parent().parent().parent().parent().parent().find(".accordion-heading .accordion-toggle").html(title);
        });
    }

    /**
     * Build Slider specific options and inject it in propriate place at HTML page. I case there is no current slider defaults are returned, otherwise
     * ... Fields with values.
     * @return string
     */
    this.buildSliderSettings = function(){

        var that = this;
        settings = '';
        if(this.new_slider == true && jQuery.isEmptyObject(this.settings[this.type])){
            settings = this.getSettingsTpl();
        }
        else{
            settings = this.getSettings();
        }

        var output = '';
        var slides = '';
      
        for(var item in settings[this.type]['settings']){ 
            //slider specific opts
            output += this.parseField(settings[this.type]['settings'][item], this.type+"__settings");
        }

        jQuery(".slider-settings").after(output);

        //Does slider already has at least one slide?
        if(this.new_slider == false || this.getNumberOfSlides()>0){ 
            for(var item in settings[this.type]['item_data']){ //slider specific opts
                    slides += this.generateNewSlide(settings[this.type]['item_data'][item]);
            }
            jQuery("#slides_container").prepend(slides);
            self.afterSlidesInDom();
            //Make slides sortable
            jQuery( "#slides_container" ).sortable();
        }
    }   
}

function PieceSliderClass(name, settings){
    
    var self = this; // Self reference. Use this if we are dealing with calling public methods from private ones.
    /* Call Super Class Constructor with all arguemnts */
    SuperSliderClass.apply(this, arguments);
    
    // Overwrite or define new attribs and methods
    this.type = 'piece_slider';
    
    this.afterSlidesInDom = function(){
        
        //When user start to type slide name populate this data at main slide bar
        jQuery("input[name$='slide_name']").keyup(function(event){
            var $this = jQuery(this);
            var title = $this.val().toUpperCase();// + String.fromCharCode(event.keyCode);

            if(title == ''){ title="SLIDE TITLE";}
            $this.parent().parent().parent().parent().parent().parent().find(".accordion-heading .accordion-toggle").html(title);
        });
    }

    /**
     * Build Slider specific options and inject it in propriate place at HTML page. I case there is no current slider defaults are returned, otherwise
     * ... Fields with values.
     * @return string
     */
    this.buildSliderSettings = function(){

        var that = this;
        /* Ubaciti logiku ako je postojeci slider onda uzima vrednosti iz drugog niza ili bolje da ovo odradaim U PHP.....*/
        settings = '';

        if(this.new_slider == true && jQuery.isEmptyObject(this.settings[this.type])){
            settings = this.getSettingsTpl();
        }
        else{
            settings = this.getSettings();
        }
        
        var output = '';
        var slides = '';
      
        for(var item in settings[this.type]['settings']){ 
            //slider specific opts
            output += this.parseField(settings[this.type]['settings'][item], this.type+"__settings");
        }
        
        for(var item in settings[this.type]['transition']){ 
            //slider specific opts
            output += this.parseField(settings[this.type]['transition'][item], this.type+"__transition");
        }

        jQuery(".slider-settings").after(output);

        //Does slider already has at least one slide?
        if(this.new_slider == false || this.getNumberOfSlides()>0){ 
            for(var item in settings[this.type]['item_data']){ //slider specific opts
                    slides += this.generateNewSlide(settings[this.type]['item_data'][item]);
            }
            jQuery("#slides_container").prepend(slides);
            self.afterSlidesInDom();
            //Make slides sortable
            jQuery( "#slides_container" ).sortable();
        }
    }  
    
    /**
     * Invoked after  user click on Add New Slide button
     */
    this.addNewSlide = function(){
        var slide = this.generateNewSlide();
        jQuery("#slides_container").prepend(slide);
        manageAdminOptions();
    }
    
   
}// End PieceMaker Slider


//Extend Abstract class
//AnythingSliderClass.prototype   = new SuperSliderClass();
PieceSliderClass.prototype         = new SuperSliderClass();
CycleSliderClass.prototype         = new SuperSliderClass();

/* Vrava true ako je v2 > v1  */
function compareVersion(v1, v2){
    this.v1 = v1;
    this.v2 = v2;

    v1_arr = this.v1.split(".");
    v2_arr = this.v2.split(".");

    var multiplyer = 1000000;
    v1_score = 0;
    v2_score = 0;

    for(var i=0; i < v1_arr.length; i++){
        v1_score += v1_arr[i]*multiplyer;
        multiplyer = multiplyer/100;
    }

    multiplyer = 1000000;
    for(var i=0; i < v2_arr.length; i++){
        v2_score += v2_arr[i]*multiplyer;
        multiplyer = multiplyer/100;
    }

    if(v2_score >= v1_score) {
        return true;
    }
    else{
        return false;
    }
}