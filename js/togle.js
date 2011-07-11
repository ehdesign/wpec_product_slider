

jQuery(document).ready(function() {
	if (jQuery("input[name='wpec-ps-radio-setting']:checked").val() == '0') {
			jQuery('#wpec-ps-hiden-options').css("display","block");
	} else {
			jQuery('#wpec-ps-hiden-options').css("display","none");
	}
	jQuery("input[name='wpec-ps-radio-setting']").change(
		function()
		{
			
			if (jQuery("input[name='wpec-ps-radio-setting']:checked").val() == '0')
				jQuery('#wpec-ps-hiden-options').slideDown(400);
			else
				jQuery('#wpec-ps-hiden-options').slideUp(400);
		});

});
