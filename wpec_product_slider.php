<?php
/*
Plugin Name:Products Slider for WP E-Commerce
Plugin URI: http://getshopped.org
Description: A plugin that provides a slider for product list
Author: Instinct
Author URI: http://getshopped.org
Developer: Roy Ho
Developer URI: http://1plusdesign.com
Version: 2.1
License: Commericial
*/

/**
* necessary to prevent image flicker on hover.
*/

//EH Design added to create GLOBAL variable Scope
$ulCounter = 0;
//END EH Design add

function product_slider_preload() {
	$theme = product_slider_cur_theme();
	echo "<script type='text/javascript'>\r\n";
	echo "if (document.images)\r\n";
	echo "{\r\n";
	echo "image1= new Image(34,70);\r\n";
	echo "image1.src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/".$theme."/images/rounded_prev_hover.png\"\r\n";
	echo "image2= new Image(34,70);\r\n";
	echo "image2.src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/".$theme."/images/rounded_next_hover.png\"\r\n";
	echo "}\r\n";
	echo "</script>\r\n";
}

$gold_shpcrt_active = get_option('activation_state');
if ($gold_shpcrt_active === 'true') {
	$theme = product_slider_cur_theme();
	register_activation_hook(__FILE__,'wpec_product_slider_install');
	register_deactivation_hook( __FILE__, 'wpec_product_slider_remove' );	
	add_action('wp_footer', 'product_slider_preload');		
	add_action('wp_head', 'product_slider_css'); 

	
	function wpec_product_slider_add_scripts(){
		wp_enqueue_script('product_slider_options', WP_PLUGIN_URL."/wpec_product_slider/js/togle.js", array('jquery'), '1.0.3');
	}
	
	function wpec_product_slider_install() {
		$options = array('theme' => 1,'visible' => 4,'scrolls' => 1,'display_price' => 0,'display_type' => 0);
		add_option('wpec_product_slider',$options,'','yes');	
	}
	
	function wpec_product_slider_remove() {
		delete_option('wpec_product_slider');		
	}

	function wpec_product_slider_init() {
		
			$theme = product_slider_cur_theme();
			if ($theme == "wordpress") {	
				wp_enqueue_script('jcarousellite', WP_PLUGIN_URL."/wpec_product_slider/js/jcarousellite_custom.js", '','',true);
				wp_enqueue_script('product_slider_wordpress', WP_PLUGIN_URL."/wpec_product_slider/js/wordpress.js", array('jquery', 'livequery','jcarousellite'),'',true);
			}else{
				//wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-slider', WP_PLUGIN_URL."/wpec_product_slider/js/jquery-ui-slider.js", array('jquery', 'livequery'),'',true);
				wp_enqueue_script('product_slider_apple', WP_PLUGIN_URL."/wpec_product_slider/js/apple.js", array('jquery', 'livequery','jquery-ui-slider'),'',true);
			}
		
		if ( is_admin() ) 
			add_filter( 'wpsc_additional_pages', 'wpec_product_slider_admin_pages',10,2 );
		}	
	}

	function wpec_product_slider_admin_pages($page_hooks, $base_page) {
		$page_hooks[] = add_submenu_page($base_page, __('Product Slider','wpsc'), __('- Product Slider','wpsc'), 7, 'product_slider', 'wpec_product_slider_options_form');
		add_action('admin_init','wpec_product_slider_add_scripts');
		return $page_hooks;
	}
	
	function wpec_product_slider_options_form() {
		if( !current_user_can('manage_options') ) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		global $wpdb; 
			$options = $newoptions = get_option('wpec_product_slider');
			if ( $_POST["wpec_product_slider_submit"] ) {
				$newoptions['theme'] = $_POST["wpec-ps-radio-setting"];
				$newoptions['visible'] = $_POST["wpec_product_slider_visible"];
				$newoptions['scrolls'] = $_POST["wpec_product_slider_scrolls"];
				$newoptions['display_price'] = $_POST["wpec_product_slider_display_price"];
				$newoptions['display_type'] = $_POST["wpec_product_slider_display_type"];
			}
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('wpec_product_slider', $options); ?>
                	<div class="updated settings-error">
						<p><strong>Settings saved.</strong></p>
					</div><!--close updated settings-error-->
		<?php }		
		?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div> <h2> WP-e-Commerce Product Slider Settings</h2>
		<p>Choose from these settings to set up your product slider <br />
		<em> Note: WordPress is the recommended theme for the Product Slider </em><br />
		
		<?php 
			if (product_slider_cur_theme() == "wordpress") {
				$checked_wordpress = 'checked="checked"';
			} else {
				$checked_apple = 'checked="checked"';	
			}
		?>	
		<form name="options_form" method="post" action="">
		
			<table>
				<tr>
					<th scope="row" width="150px;" align="right"><label for="theme">Select a Theme:</label></th>
						<td>
							<label>	<input type='radio' name='wpec-ps-radio-setting' <?php echo $checked_wordpress; ?> value='0'  /> Wordpress</label>
							<label>	<input type='radio' name='wpec-ps-radio-setting'  <?php echo $checked_apple; ?> value='1' />Apple</label>
						</td>
				</tr>
			</table>	
		
		<div class="misc-pub-section" id="wpec-ps-hiden-options">
    
   			<table>
       			 <tr>
					<th scope="row" width="150px;" align="right"><label for="visible">Visible:</label></th>
						<td>
							<select name="wpec_product_slider_visible" style="width:100px"> 
	                   			 <?php for($i = 0; $i <= 10; $i++){
								 	 $selected=''; if ($i==$options['visible']) $selected=" SELECTED ";  echo "<option".$selected." value='".$i."'>";
							  		 echo "{$i}</option>";
								  } ?>
	                    	</select> 
						</td>
                
                		<td>
               				<em>Choose the number of products to be visible at one time. (10 max)</em> 
                		</td>
				</tr>
			<tr>
				<th scope="row" width="150px;" align="right"><label for="scrolls">Product Scroll By:</label></th>
					<td>
						<select name="wpec_product_slider_scrolls" style="width:100px"> 
	                    <?php for($i = 1; $i <= 10; $i++){
								  $selected=''; if ($i==$options['scrolls']) $selected=" SELECTED ";  echo "<option".$selected." value='".$i."'>";
							   echo "{$i}</option>";
								  } ?>
	                    </select>
	                    
					</td>
				
                <td>
                	<em>Choose the number of products/items to scroll on 1 click</em> 
                </td>
			</tr>
			 </table>
        
</div>
<div class="misc-pub-section" id="toggle-search">
	<table>
		<tr>
			<th scope="row" width="150px;" align="right"><label for="display_type">Type:</label></th>
				<td>
					<select name="wpec_product_slider_display_type" style="width:100px"> 
                    <?php for($i = 0; $i <= 1; $i++){
							  $selected=''; if ($i==$options['display_type']) $selected=" SELECTED ";  echo "<option".$selected." value='".$i."'>";
							  switch ($i) {
								  case 0:
									  $display_type = "Product";
									  break;
								  case 1:
									  $display_type = "Category";
									  break;
								  // add new themes here						
								  default:
									  $display_type = "Product";	
							  }
						   echo "{$display_type}</option>";
							  } ?>
                    </select>
                    
				</td>
				
                <td>
               		<em>Choose to display category or product.</em> 
                </td>
        </tr>
				
		<tr>
			<th scope="row" width="150px;" align="right"><label for="display_price">Display Price:</label></th>
				<td>
					<select name="wpec_product_slider_display_price" style="width:100px"> 
	                    <?php for($i = 0; $i <= 1; $i++){
								  $selected=''; if ($i==$options['display_price']) $selected=" SELECTED ";  echo "<option".$selected." value='".$i."'>";
							   if ($i == 0) {
										  $price = "No";
							   }else{
										  $price = "Yes";
							   }
							   echo "{$price}</option>";
								  } ?>
                    </select>
				</td>
                
                <td>
                	<em>Display price under product image. (This setting does not affect Category display type)</em> 
                </td>
		</tr>

	</table>
</div>
		<p class="submit">
			<input type="submit" name="wpec_product_slider_submit" value="Save Changes" />
		</p>
			<input type="hidden" name="action" value="update" />
	</form>
    </div><!--close wrap-->
	<?php
		}
	
	function product_slider_css() {
		$theme = product_slider_cur_theme();
		?>
			 <link href='<?php echo WP_PLUGIN_URL; ?>/wpec_product_slider/themes/<?php echo $theme; ?>/style.css' rel="stylesheet" type="text/css" media="screen" />          
             
	<?php }
	
	// apple template style
	function product_slider_template_apple() {
		global $ulCounter;
		
		//EH Design add - set up unique ID
		$ulCounter++;
		$galID = 'gaID' . $ulCounter;
		$randID = 'ulID' . $ulCounter;
		//EH - END SET UP RANDOM ID
		
		$options = get_option('wpec_product_slider');
		$theme = product_slider_cur_theme();
		$display_price = $options['display_price'];	
		$display_type = $options['display_type'];
		$product_image_width = get_option('product_image_width');
		$product_image_height = get_option('product_image_height');		
		$category_image_width = get_option('category_image_width');
		$category_image_height = get_option('category_image_height');		
		//if display type is product
		if($display_type == 0) {
        echo "<div id=\"sliderWrap\">\r\n";
		echo "<div id=\"" . $galID . "\" class=\"sliderGallery group\">\r\n";
		echo "<img src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/{$theme}/images/drop_spinner.gif\" alt=\"Load Spinner\" id=\"dragdrop_spinner_slider\" />";
		
		
		
		//EH Design - added ID here to call from jquery
		echo "<ul id='" . $randID . "'>\r\n";			
                    
            while (wpsc_have_products()) :  wpsc_the_product();
                        echo "<li>\r\n";
                            
                            
                            
                            echo "<a href=\"".wpsc_the_product_permalink()."\">\r\n";
                            echo "<img class=\"item product_image\" id=\"product_image_".wpsc_the_product_id()."\" alt=\"".wpsc_the_product_title()."\" title=\"".wpsc_the_product_title()."\" src=\"".wpsc_the_product_thumbnail($product_image_width,$product_image_height)."\" />\r\n";
                            echo "</a>\r\n";
            
                            echo "<a href=\"#\" class=\"add_product\">\r\n";
                            echo "<img width=\"27\" height=\"27\" class=\"add\" alt=\"add\" src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/{$theme}/images/product_add.png\" />\r\n";
                            echo "</a>\r\n";
                            echo "<p class=\"meta\" style='width:".$product_image_width."px'>\r\n";
                            echo "<a href=\"".wpsc_the_product_permalink()."\">";
							  $description = wpsc_the_product_title();
							  if (strlen($description) > 10){
							  $description = substr($description,0,8);
							  echo "{$description}...</a>\r\n";
							  }else{
								echo "{$description}</a>\r\n";  
							  }
							if ($display_price == "1") {
                            echo "<br /><em>".wpsc_the_product_price()."</em>\r\n";
							}
							echo "</p>\r\n";
                            echo "<form class=\"product_form\" id=\"product_".wpsc_the_product_id()."\" name=\"product_".wpsc_the_product_id()."\" method=\"post\" action=\"\" enctype=\"multipart/form-data\">\r\n";
                                echo "<input type=\"hidden\" name=\"wpsc_ajax_action\" value=\"add_to_cart\" />\r\n";
                                echo "<input type=\"hidden\" name=\"product_id\" value=\"".wpsc_the_product_id()."\" />\r\n";
                                echo "<input type=\"hidden\" name=\"key\" value=\"".wpsc_the_cart_item_key()."\"/>\r\n";
                                echo "<input type=\"hidden\" name=\"wpsc_update_quantity\" value=\"true\" />\r\n";
								echo "<input type=\"hidden\" name=\"product_image_width\" value=\"{$product_image_width}\" />\r\n";
								/*if (wpsc_have_variation_groups()) {
                        		echo "<ul class=\"variationsLoop\">\r\n";
									while (wpsc_have_variation_groups()) : wpsc_the_variation_group();
										echo "<li>\r\n";
										echo "<label for=\"".wpsc_vargrp_form_id()."\">".wpsc_the_vargrp_name().":</label>\r\n";
										
										echo "<select class='wpsc_select_variation' name=\"variation[".wpsc_vargrp_id()."]\" id=\"".wpsc_vargrp_form_id()."\">\r\n";
											if (wpsc_have_variations()) {
											while (wpsc_have_variations()) : wpsc_the_variation();
												echo "<option value=\"".wpsc_the_variation_id()."\" ".wpsc_the_variation_out_of_stock().">".wpsc_the_variation_name()."</option>\r\n";
											endwhile;
											}
										echo "</select>\r\n";
											
									echo "</li>\r\n";
									endwhile;
                                echo "</ul>\r\n";
								}*/
								
                            echo "</form>\r\n";
                        echo "</li>\r\n";
                    endwhile;
                    
                echo "</ul>\r\n";
				echo "<div class=\"sliderContainer\">\r\n";
				//EH Design - added ID here to call from jquery
				echo "<span id=\"spnL" . $randID . "\" class=\"btn-left\"></span>\r\n";
				//EH Design - added ID here to call from jquery
				echo "<span id=\"spnS" . $randID . "\" class=\"start\"></span>\r\n";
				//EH Design - added ID here to call from jquery
				echo "<span id=\"spnR" . $randID . "\" class=\"btn-right\"></span>\r\n";
				//EH Design - added ID here to call from jquery
				echo "<span id=\"spnE" . $randID . "\" class=\"end\"></span>\r\n";
				echo "<div class=\"slider\" id=\"sldr" . $randID . "\">";
				//EH Design - added ID here to call from jquery
				echo "<div id=\"divs" . $randID . "\" class=\"ui-slider-handle\"></div>\r\n";
				echo "</div>\r\n";
				echo "</div>\r\n";
            echo "</div><!--close sliderGallery-->\r\n";     
        echo "</div><!--close sliderWrap-->\r\n";
		//if display type is category
		}elseif ($display_type == 1) {
			
        echo "<div id=\"sliderWrap\">\r\n";
		echo "<img src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/{$theme}/images/drop_spinner.gif\" alt=\"Load Spinner\" id=\"dragdrop_spinner_slider\" />";
		echo "<div class=\"sliderGallery\">\r\n";
			echo "<ul>\r\n";
				wpsc_start_category_query(array('category_group'=>get_option('wpsc_default_category'), 'show_thumbnails'=> get_option('show_category_thumbnails')));
						echo "<li>\r\n";
							 echo '<a href="';
							 wpsc_print_category_url();
							 echo '" title="">';
							 echo wpsc_print_category_image($category_image_width, $category_image_height);
							 echo '</a>'."\r\n";					
                        	echo '<p class="meta" style="width:'.$category_image_width.'px">'."\r\n";
							echo "<a href=\"";
							wpsc_print_category_url();
							echo "\">";
							wpsc_print_category_name();
							  echo "</a></p>\r\n";
						echo "</li>\r\n";
						wpsc_print_subcategory();
				wpsc_end_category_query();
				echo "<input type=\"hidden\" name=\"product_image_width\" value=\"{$category_image_width}\" />\r\n";
			echo "</ul>\r\n";
				echo "<div class=\"sliderContainer\">\r\n";
				echo "<span class=\"btn-left\"></span>\r\n";
				echo "<span class=\"start\"></span>\r\n";
				echo "<span class=\"btn-right\"></span>\r\n";
				echo "<span class=\"end\"></span>\r\n";
				echo "<div class=\"slider\">";
				echo "<div class=\"ui-slider-handle\"></div>\r\n";
				echo "</div>\r\n";
				echo "</div>\r\n";
            echo "</div><!--close sliderGallery-->\r\n";     
        echo "</div><!--close sliderWrap-->\r\n";
		}
    }

	// wordpress template style
	function product_slider_template_wordpress() {
		global $ulCounter;
		global $wp_query, $wpsc_query;
		$options = get_option('wpec_product_slider');
		$product_image_width = get_option('product_image_width');
		$product_image_height = get_option('product_image_height');
		$category_image_width = get_option('category_image_width');
		$category_image_height = get_option('category_image_height');		
		$theme = product_slider_cur_theme();
		$visible = $options['visible'];
		$scrolls = $options['scrolls'];	
		$display_price = $options['display_price'];	
		$display_type = $options['display_type'];
		//if display type is product
		if ($display_type == 0) {
			//EH Design add - set up unique ID
			$ulCounter++;
			$randID = 'ulID' . $ulCounter;
			//EH - END SET UP RANDOM ID		
			echo "<div id=\"product_scroll\" class=\"group\">\r\n";
			echo "<div id=\"" . $randID . "\" class=\"gallery_slider\">\r\n";
			echo "<img src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/{$theme}/images/drop_spinner.gif\" alt=\"Load Spinner\" id=\"dragdrop_spinner_slider\" />";
			// below hidden inputs passes values to JS
			echo "<input type='hidden' id='wpec_slider_visibles' value='{$visible}' />\r\n";
			echo "<input type='hidden' id='wpec_slider_scrolls' value='{$scrolls}' />\r\n";	
			//echo "<input type='hidden' id='wpec_slider_image_width' value='{$product_image_width}' />\r\n";
			//echo "<input type='hidden' id='wpec_slider_image_height' value='{$product_image_height}' />\r\n";			
			echo "<a href=\"#\" title=\"Previous\" class=\"prev\">Previous</a>\r\n";
			echo "<ul>\r\n";			
                //    exit('<pre>'.print_r($wp_query,1).'</pre>');
            while (wpsc_have_products()) :  wpsc_the_product();

                        echo "<li>\r\n";
                            echo "<p class=\"meta\">\r\n";
                            echo "<a href=\"".wpsc_the_product_permalink()."\">";
							  $description = wpsc_the_product_title();
							  if (strlen($description) > 10){
							  $description = substr($description,0,10);
							  echo "{$description}...</a>\r\n";
							  }else{
								echo "{$description}</a>\r\n";  
							  }
							if ($display_price == "1") {
                            echo "<em>".wpsc_the_product_price()."</em>\r\n";
							}
                            echo "</p>\r\n";
                            
                            echo "<a href=\"".wpsc_the_product_permalink()."\">\r\n";
                            echo "<img class=\"item product_image\" id=\"product_image_".wpsc_the_product_id()."\" alt=\"".wpsc_the_product_title()."\" title=\"".wpsc_the_product_title()."\" src=\"".wpsc_the_product_thumbnail($product_image_width,$product_image_height)."\" />\r\n";
                            echo "</a>\r\n";
            
                            echo "<a href=\"#\" class=\"add_product\">\r\n";
                            echo "<img width=\"27\" height=\"27\" class=\"add\" alt=\"add\" src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/{$theme}/images/product_add.png\" />\r\n";
                            echo "</a>\r\n";
                            
                            echo "<form class=\"product_form slider\" id=\"product_".wpsc_the_product_id()."\" name=\"product_".wpsc_the_product_id()."\" method=\"post\" action=\"\" enctype=\"multipart/form-data\">\r\n";
                                echo "<input type=\"hidden\" name=\"wpsc_ajax_action\" value=\"add_to_cart\" />\r\n";
                                echo "<input type=\"hidden\" name=\"product_id\" value=\"".wpsc_the_product_id()."\" />\r\n";
                                echo "<input type=\"hidden\" name=\"key\" value=\"".wpsc_the_cart_item_key()."\"/>\r\n";
                                echo "<input type=\"hidden\" name=\"wpsc_update_quantity\" value=\"true\" />\r\n";
								/*if (wpsc_have_variation_groups()) {
                        		echo "<ul class=\"variationsLoop\">\r\n";
									while (wpsc_have_variation_groups()) : wpsc_the_variation_group();
										echo "<li>\r\n";
										echo "<label for=\"".wpsc_vargrp_form_id()."\">".wpsc_the_vargrp_name().":</label>\r\n";
										
										echo "<select class='wpsc_select_variation' name=\"variation[".wpsc_vargrp_id()."]\" id=\"".wpsc_vargrp_form_id()."\">\r\n";
											if (wpsc_have_variations()) {
											while (wpsc_have_variations()) : wpsc_the_variation();
												echo "<option value=\"".wpsc_the_variation_id()."\" ".wpsc_the_variation_out_of_stock().">".wpsc_the_variation_name()."</option>\r\n";
											endwhile;
											}
										echo "</select>\r\n";
											
									echo "</li>\r\n";
									endwhile;
                                echo "</ul>\r\n";
								}*/
                            echo "</form>\r\n";
                        echo "</li>\r\n";
                    endwhile;
                    
                echo "</ul>\r\n";
				echo "<a href=\"#\" title=\"Next\" class=\"next\">Next</a>\r\n";
            echo "</div><!--close gallery_slider-->\r\n";     
        echo "</div><!--close product_scroll-->\r\n";
		
		//if display type is category
		
		}elseif ($display_type == 1) {
        echo "<div id=\"product_scroll\" class=\"group\">\r\n";
        echo "<div class=\"gallery_slider\">\r\n";
		echo "<img src=\"".WP_PLUGIN_URL."/wpec_product_slider/themes/{$theme}/images/drop_spinner.gif\" alt=\"Load Spinner\" id=\"dragdrop_spinner_slider\" />";
		// below hidden inputs passes values to JS
        echo "<input type='hidden' id='wpec_slider_visibles' value='{$visible}' />\r\n";
        echo "<input type='hidden' id='wpec_slider_scrolls' value='{$scrolls}' />\r\n";	
		//echo "<input type='hidden' id='wpec_slider_image_width' value='{$product_image_width}' />\r\n";
		//echo "<input type='hidden' id='wpec_slider_image_height' value='{$product_image_height}' />\r\n";			
        echo "<a href=\"#\" title=\"Previous\" class=\"prev\">Previous</a>\r\n";
	
			echo "<ul>\r\n";
				wpsc_start_category_query(array('category_group'=>get_option('wpsc_default_category'), 'show_thumbnails'=> 1));
						echo "<li>\r\n";
                        	echo "<p class=\"meta\">\r\n";
							echo "<a href=\"";
							wpsc_print_category_url();
							echo "\">";
							wpsc_print_category_name();
							  echo "</a></p>\r\n"; 
							  //echo "<a href=\"\" title=\"\" style=\"width:{$category_image_width}px; height:{$category_image_height}px; display:block;\">".wpsc_print_category_image(96, 96)."</a>\r\n";	
							echo "<a href=\"";
							wpsc_print_category_url();
							echo "\">";							  				
							wpsc_print_category_image($category_image_width, $category_image_height);
							echo "</a>\r\n";							
						echo "</li>\r\n";
						wpsc_print_subcategory();
				wpsc_end_category_query();
			echo "</ul>\r\n";
				echo "<a href=\"#\" title=\"Next\" class=\"next\">Next</a>\r\n";
            echo "</div><!--close gallery_slider-->\r\n";     
        echo "</div><!--close product_scroll-->\r\n";	
		}
		echo "<div style='clear:both;'></div>";
    }
    
	function product_slider_convert_query($query){
		$args['post_type'] = 'wpsc-product';
		if(!empty($query['product_id']) && is_array($query['product_id'])){
			$args['post__in'] = $query['product_id'];
		}elseif(is_string($query['product_id'])){
			$args['post__in'] = (array)$query['product_id'];
		}
		if(!empty($query['price']) && 'sale' != $query['price']){
			$args['meta_key'] = '_wpsc_price';
			$args['meta_value'] = $query['price'];
		}elseif(!empty($query['price']) && 'sale' == $query['price']){
			$args['meta_key'] = '_wpsc_special_price';
			$args['meta_compare'] = '>=';
			$args['meta_value'] = '1';
		}
		if(!empty($query['product_name'])){
			$args['pagename'] = $query['product_name'];
		}
		if(!empty($query['category_id'])){
			//unset($args['post_type']);

			$term = get_term($query['category_id'],'wpsc_product_category');
			$id = wpsc_get_meta($query['category_id'], 'category_id','wpsc_old_category');
			if( !empty($id)){
				$term = get_term($id,'wpsc_product_category');
				$args['wpsc_product_category'] = $term->slug;
				$args['wpsc_product_category__in'] = $term->term_id;
			}else{
				$args['wpsc_product_category'] = $term->slug;
				$args['wpsc_product_category__in'] = $term->term_id;
			}
		}
		if(!empty($query['category_url_name'])){
			$args['wpsc_product_category'] = $query['category_url_name'];
		}
		if(!empty($query['sort_order'])){
			$args['orderby'] = $query['sort_order'];
		}
		if(!empty($query['order'])){
			$args['order'] = $query['order'];
		}
		if(!empty($query['limit_of_items'])){
			$args['posts_per_page'] = $query['limit_of_items'];
		}	
		if(!empty($query['number_per_page'])){
			$args['posts_per_page'] = $query['number_per_page'];
		}	
		if(!empty($query['tag'])){
			$args['product_tag'] = $query['tag'];
		}
		return $args;
	
	}
	// template tag
	function wpec_product_slider($query) {
		global $wpdb, $wp_query, $wpsc_query;
		$siteurl=get_option('wpurl');
		if((float)WPSC_VERSION >= 3.8 ){
			$query = product_slider_convert_query($query);
			$temp_wpsc_query = new WP_Query($query);
			list($wp_query, $temp_wpsc_query) = array($temp_wpsc_query, $wp_query); // swap the wpsc_query objects
		}else{
			$temp_wpsc_query = new WPSC_query($query);
			list($wpsc_query, $temp_wpsc_query) = array($temp_wpsc_query, $wpsc_query); // swap the wpsc_query objects
		}	
		$theme = product_slider_cur_theme();
		if ($theme == "apple") {
			product_slider_template_apple();	
		}else{
  			product_slider_template_wordpress();
		}
		if((float)WPSC_VERSION >= 3.8 ){
		//	return;
			list( $temp_wpsc_query, $wp_query) = array($wp_query, $temp_wpsc_query); // swap the wpsc_query objects	
		}else{
			list($temp_wpsc_query, $wpsc_query) = array($wpsc_query, $temp_wpsc_query); // swap the wpsc_query objects back
		}
		return $output;
	}

	function show_product_slider($content = '') {
		if(preg_match("/\[wpec_slider_category=([\d]+)]/",$content,$matches)) {
			$category_id = $matches[1];
			$GLOBALS['nzshpcrt_activateshpcrt'] = true;
			$output = wpec_product_slider(array('category_id' =>$category_id));
			return preg_replace("/\[wpec_slider_category=([\d]+)]/",$output, $content);
		} else {
			return $content;
		}
	}
	
	function wpec_product_slider_shorttag($atts) {
		$query = shortcode_atts(array(
			'product_id' => 0,
			'product_url_name' => null,
			'product_name' => null,
			'category_id' => 0,
			'category_url_name' => null,
			'tag' => null,
			'price' => 0,
			'limit_of_items' => 0,
			'sort_order' => null,
			'number_per_page' => 0,
			'page' => 0,
		), $atts);
		
		return wpec_product_slider($query);
	}
	add_shortcode('wpec_product_slider', 'wpec_product_slider_shorttag');
	//add_filter('wp_content', 'show_product_slider');
	add_action( 'init','wpec_product_slider_init' ); 


// gets the current theme
function product_slider_cur_theme() {
	$options = get_option('wpec_product_slider');
	$cur_theme = $options['theme'];
	
	switch ($cur_theme) {
		case 0:
			$theme = "wordpress";
			break;
		case 1:
			$theme = "apple";
			break;
		// add new themes here	
		default:
			$theme = "wordpress";	
	}
	return $theme;
}



?>