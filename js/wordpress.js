jQuery(window).load(function(){
	//var width = jQuery('#wpec_slider_image_width').val();
	//width = parseInt(width,10);
	//var height = jQuery('#wpec_slider_image_height').val();
	//height = parseInt(height,10);
	//jQuery(".gallery_slider ul li").css('width',width).css('height',height);	
	var visibles = jQuery('#wpec_slider_visibles').val();
	var scrolls = jQuery('#wpec_slider_scrolls').val();
	visibles = parseInt(visibles, 10);
	scrolls = parseInt(scrolls, 10);
	
	
	jQuery(".gallery_slider").livequery(function(){
		var ehid = "#" + this.id;
		var ehidN = ehid + " .next";
		var ehidP = ehid + " .prev";
		jQuery(this).jCarouselLite({
			btnNext: ehidN,
			btnPrev: ehidP,
			visible: visibles,
			scroll: scrolls,
			speed: 200
		});
	});	
	
	jQuery(".gallery_slider").each(
	function(intIndex){		
		//set up the jquery stuff in here...
		
	});
	
}); //close doc ready



jQuery(document).ready(function(){
	
	

	jQuery('a.add_product').livequery( function() {
		jQuery(this).click(function() {
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
	});	
	
}); //close doc ready

jQuery(document).ready(function() {
	 // hides the slickbox as soon as the DOM is ready
	  jQuery('#toggle-search').hide();

	 // toggles the slickbox on clicking the noted link
	  jQuery('a#slick-slidetoggle').click(function() {
		jQuery('#toggle-search').slideToggle(400);
		return false;
	  });
	});


	



