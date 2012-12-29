/**
 *  Slider based on jquery.ui.slider.js
 */
(function($) {
    
    $.fn.mlsNumberSlider = function(method) {
    
        var methods = {
              init : function(options){
                  return this.each(function() {
                      var $this = $(this);
                      
                      var txtval = $this.parent().find("input");//data('value');
                      step  = $this.data('step');
                      min   = $this.data('min');
                      max   = $this.data('max');
                      
                      sl = $this.slider(
                              {
                                  value:txtval.val(),
                                  min: min,
                                  max: max,
                                  step: step,
                                  animate: true,
                                  slide: function( event, ui ) {
                                      $(txtval).val(ui.value );
                                  }
                      });
                      txtval.val( $this.slider( "value" ) );
                      txtval.on('blur', txtval, methods.updateInfo);
                  });// Main Return End
              },
              updateSlider : function(event){},
              updateInfo : function(event){
                  var $thistxt = $(this);
                 // var slider = 
              }
        }; // Methods Ends Here
        
        // Method calling logic
        if ( methods[method] ) { //Method exists. Apply it.
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) { // is object or not defined. Call Init
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist in this plugin!' );
        }  
    };  
}(jQuery));