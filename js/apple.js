jQuery(document).ready(function(){
			var initLength = 0;
			var factor = new Array();
			var counter = 0;
			//eh Add to iterate through ALL sliderGallery
			jQuery('div.sliderGallery').each(function(i){
				counter++;
				 var container = this;
				 var GALid= this.id;
				 var ul = jQuery('ul', container);
				 var product_width = jQuery("input[name=product_image_width]").val();
				 var itemsWidth = parseInt(product_width);
				 var numli = jQuery('#' + GALid + ' > ul > li').size();
				 if (initLength == 0) {
				 	initLength = numli;
				 }
				 if (numli > initLength) {
				 	var diff = numli - initLength;
				 	factor[counter] = 130 * (1.5 +(numli - initLength));
				 } else {
				 	factor[counter] = 130;	
				 }
				jQuery('.slider', container).slider({
				min: 0,
				max: itemsWidth + factor[counter],
				handle: '.ui-slider-handle',
				stop: function (event, ui) {
						//get current elements ID, remove front four and then access corresponding UL
						var x = ui.handle.id;
						var x = 'ul#' + x.substring(4);
						var ehUL = jQuery(x, container);
						ehUL.animate({'left' : ui.value * -1}, 500);
				},
				slide: function (event, ui) {
					//get current elements ID, remove front four and then access corresponding UL
					var x = ui.handle.id;
					var x = 'ul#' + x.substring(4);
					var ehUL = jQuery(x, container);
					ehUL.css('left', ui.value * -1);
				}
				
			});
			});
			
			var container = jQuery('div.sliderGallery');
            var ul = jQuery('ul', container);
			var product_width = jQuery("input[name=product_image_width]").val();
			var itemsWidth = parseInt(product_width) + 200; // 46 is the total pixels for the margin and padding. add another 45% to accomodate for different sizes
           // container.css('overflow', 'hidden');  // move auto to noscript style to avoid flicker - ADDED TO CSS SHEET
           
			
	       jQuery(".btn-left").click(function(){
	      	   var x = this.id;
			   var s = '#sldr' + x.substring(4);
			   var x = 'ul#' + x.substring(4);
		       var elValue = jQuery(s, container).slider('option', 'value');
		          // alert (elValue);
			       if(elValue > 0) {
			          elValue = elValue - 100;
			          if(elValue < 0) {
		             elValue = 0;
		          }
			     jQuery(s).slider('value', elValue);
		         jQuery(x).animate({'left' : elValue * -1}, 500);
			       }
			});
			jQuery(".btn-right").click(function(){
			  var x = this.id;
			  var y = x.substring(8);
			  var s = '#sldr' + x.substring(4);
			  var x = 'ul#' + x.substring(4);
			  var elValue = jQuery(s, container).slider('option', 'value');
		       // alert (elValue);
			       if(elValue < (itemsWidth + factor[y] - 150)) {
			          elValue = elValue + 100; 
			          if(elValue > (itemsWidth + factor[y] - 150)) {
		             elValue = (itemsWidth + factor[y] - 150);
		          }
		          jQuery(s).slider('value', elValue); 
		          jQuery(x).animate({'left' : elValue * -1}, 500);
			       }
			});
			jQuery("span.end").click(function(){
			  var x = this.id;
			  var y = x.substring(8);
			  var s = '#sldr' + x.substring(4);
			  var x = 'ul#' + x.substring(4);
			  var elValue = jQuery(s, container).slider('option', 'value');
		          elValue = itemsWidth + factor[y] - 150;
		          jQuery(s).slider('value', elValue); 
		          jQuery(x).animate({'left' : elValue * -1}, 500);
			});
			jQuery("span.start").click(function(){
			  var x = this.id;
			  var s = '#sldr' + x.substring(4);
			  var x = 'ul#' + x.substring(4);
			  var elValue = jQuery(s, container).slider('option', 'value');
		       // alert (elValue);
		             elValue = 0;
		          jQuery(s).slider('value', elValue); 
		          jQuery(x).animate({'left' : elValue * -1}, 500);
			});


	jQuery('a.add_product').click( function() {
		jQuery("#dragdrop_spinner_slider").css('display', 'block');
		jQuery(this).attr('href', 'javascript:void(0);');
		form_values = jQuery("form", jQuery(this).parent()).serialize();
		jQuery.post( 'index.php?ajax=true&user=true&drag_and_drop_cart=true', form_values, function(returned_data) {
																									
			eval(returned_data);
			jQuery("#dragdrop_spinner_slider").css('display', 'none');
			if(jQuery("#fancy_notification") != null) {
				jQuery("#loading_animation").css('display', 'none');
				//jQuery('#fancy_notificationimage').css("display", 'none');
				
			}
			//submitform(document.getElementById(form_id));
			
			return false;
			
		});
	});	
	
}); //close doc ready


