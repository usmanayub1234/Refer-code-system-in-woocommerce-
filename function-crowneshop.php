<?php

//My Custom Code For Refferal Code (Usman) //

//For Date time second miliseco in  number format
/*$micro_date = microtime();
$date_array = explode(" ",$micro_date);
$date = date("YmdHis",$date_array[1] );
echo $date ,$date_array[0];*/

add_action( 'woocommerce_email_after_order_table', 'ts_email_after_order_table',  10, 4 );
function ts_email_after_order_table( $order, $sent_to_admin, $plain_text, $email ) {
	global $wpdb, $woocommerce, $post,$current_user;
// 	$var= 'CGC-';
// 	$random =wp_rand(1000, 99999);
// 	$fullcode= $var.''.$random;
	if ( $order->has_status( 'completed' ) ) : 
	$order = new WC_Order($post->ID);
	$order_id = trim(str_replace('#', '', $order->get_id()));
	$user_id = $order->get_user_id(); //or $order->get_customer_id();
	$tablename=$wpdb->prefix.'users';
	
	$selectQueryCou="SELECT COUNT(*) AS count FROM ".$tablename." WHERE ID=".$user_id." AND refer_code = '' LIMIT 1 ";	
	$rows = $wpdb->get_row($selectQueryCou);
	if($rows -> count > 0){
		$coupon_code =$fullcode;
		
$var= 'CGC-';
$fullcode= $var.''.uniqid();
$selectQueryCoupon="SELECT COUNT(*) AS count FROM ".$tablename." WHERE refer_code = '".$fullcode."' ";	
$couponRow = $wpdb->get_row($selectQueryCoupon);
if($couponRow -> count > 0){
//donothing
} else{
$coupon_code =$fullcode;
	}	
		$amount = '0'; // Amount
		$discount_type = 'fixed_product'; // Type: fixed_cart, percent, fixed_product, percent_product				
		$coupon = array(
			'post_title' => $coupon_code,
			'post_content' => '',
			'post_status' => 'publish',
			'0' => 1,
			'post_type'=> 'shop_coupon'
		);					
		$new_coupon_id = wp_insert_post( $coupon );					
		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', yes );
		update_post_meta( $new_coupon_id, 'product_ids', array() );
		update_post_meta( $new_coupon_id, 'exclude_product_ids','');
		update_post_meta( $new_coupon_id, 'usage_limit', '' );
		update_post_meta( $new_coupon_id, 'expiry_date', '' );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

		$updatQuery="UPDATE ".$tablename." SET refer_code='".$fullcode."' WHERE ID=".$user_id ;	
		$wpdb->query($updatQuery);			
	}else{
		//do nothing 
	}
	/*$selectQuery="SELECT COUNT(*) AS count FROM ".$tablename." WHERE ID=".$user_id." LIMIT 1";
$row = $wpdb->get_row($selectQuery);
if($row->count > 0){
}else{
//do nothing	
$data = array(refer_code => $fullcode);
$format = array('%s');
$wpdb->insert($tablename,$data,$format) ;
}*/
	$order = new WC_Order($post->ID);
	$order_id = trim(str_replace('#', '', $order->get_id()));
	$user_id = $order->get_user_id();
	$selectQueryCousend=$wpdb->get_results("SELECT * FROM ".$tablename." where ID=".$user_id." ");
	foreach($selectQueryCousend as $rowsend){ 
?>
<p> <?php printf( __('Hey %1$s. Thanks for shopping with us. As a way of saying thanks, here is your refer code for share with firends : %2$s' ), $order->get_billing_first_name(),
				 '<strong>'.$rowsend->refer_code.'</strong> <br/> And get amzing prizes.' ); ?></p>

<?php } 
	endif; 
}

// add_action( 'woocommerce_before_calculate_totals', 'discount_on_cart_per_item', 11,1 );
// function discount_on_cart_per_item( ) {
// 	global $woocommerce,$cart;  
// 	// Initialising
// 	$count = 0;
// 	$getDetails = ( new WC_Coupon($woocommerce->cart->get_applied_coupons()));
// 	$discount  =  $getDetails->amount;
// 	// Iterating though each cart items
// 	foreach ( $woocommerce->cart->get_cart() as $cart_item ) {
// 		$count++;
// 		//  item only
// 		$price = $cart_item['data']->get_price(); // product price
// 		//echo $price; 
// 		$discounted_price = $price-$discount; // calculation
// 		// Set the new price
// 		return wc_price($cart_item['data']->set_price( $discounted_price ));
// 		}	
// }


/* Prepend Facebook, Twitter and Google+ social share buttons to the post's content*/
function crunchify_social_sharing_buttons($content) {
	global $post, $current_user;
	if( is_user_logged_in() &&  is_page(13) && !is_front_page() &&  !is_wc_endpoint_url('orders') &&  !is_wc_endpoint_url('order-received') &&  !is_wc_endpoint_url('downloads') &&  !is_wc_endpoint_url('edit-account') &&  !is_wc_endpoint_url('edit-address') &&  !is_wc_endpoint_url('lost-password') &&  !is_wc_endpoint_url('customer-logout') &&  !is_wc_endpoint_url('add-payment-method')){
		// Get current page URL 
		$crunchifyTitle1 = htmlspecialchars(urlencode(html_entity_decode('&nbsp;IT PAYS TO SHARE.')));
		$crunchifyTitle = htmlspecialchars(urlencode(html_entity_decode('&nbsp;Get the discount and enjoy.')));
		$crunchifyURL = urlencode('http://www.crowneshop.com/');
		$my_content =htmlspecialchars(html_entity_decode('&nbsp;This is my referral  code to use and get discount '.$current_user->refer_code));
		// Get Post Thumbnail for pinterest
		$image =  wp_get_attachment_url( 7910 );  
		$crunchifyThumbnail = wp_get_attachment_image_src( 'https://www.crowneshop.com/wp-content/uploads/2020/02/mobile-logo.png', 'full' );

		// Construct sharing URL without using any script
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle1.''.$crunchifyTitle.''.$my_content.'&amp;url='.$crunchifyURL.''.$crunchifyThumbnail;
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL.'&quote='.$crunchifyTitle1.''.$crunchifyTitle.''.$my_content.''.$crunchifyThumbnail;
		$googleURL = 'https://plus.google.com/share?url='.$crunchifyURL.'&amp;via=CrownPremiumParts'.$my_content;
		$linkedInURL= 'https://www.linkedin.com/shareArticle?mini=true&url='.$crunchifyURL.'&title='.$crunchifyTitle.'&summary='.$my_content.'';
		$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$crunchifyURL.'&amp;media='.$crunchifyThumbnail.'&amp;description='.$crunchifyTitle1.''.$crunchifyTitle.''.$my_content.''.$crunchifyThumbnail;

		// Add sharing button at the end of page/page content
		$content .= '<div class="crunchify-social">';
		$content .= '<h5>SHARE ON</h5> <a class="crunchify-links crunchify-twitters" href="'. $twitterURL .'" target="_blank">Twitter</a>';
		$content .= '<a class="crunchify-links crunchify-facebook" href="'.$facebookURL.'" target="_blank">Facebook</a>';
		$content .= '<a class="crunchify-links crunchify-linkedin" href="'.$linkedInURL.'" target="_blank">LinkedIn</a>';
		$content .= '<a class="crunchify-links crunchify-pinterest" href="'.$pinterestURL.'" data-pin-custom="true" target="_blank">Pin It</a>';
		$content .= '</div>';
		return $content;
	}else{
		// if not a post/page then don't include sharing button
		return $content;
	}
};
add_filter( 'the_content', 'crunchify_social_sharing_buttons');


add_action( 'woocommerce_login_form', 'wooc_extra_login_fields' );
function wooc_extra_login_fields() {?>
<?php echo do_shortcode('[miniorange_social_login shape="square" theme="default" space="4" size="35"]');  ?>
<?php }; 


add_filter( 'woocommerce_variable_sale_price_html', 'theanand_remove_prices', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'theanand_remove_prices', 10, 2 );
add_filter( 'woocommerce_get_price_html', 'theanand_remove_prices', 10, 2 );
function theanand_remove_prices( $price, $product ) {
	if ( ! $product->is_in_stock()) {
		$price = '';
	}
	return $price;
}

add_filter( 'woocommerce_cart_totals_coupon_label', 'label_hide_coupon_code_custom', 99, 2 );
function label_hide_coupon_code_custom(  $label, $coupon ) {
	global $woocommerce;  
  //$coupon_amount = WC_Coupon()->cart->get_coupon_discount_amount();
	$getDetailsCou = ( new WC_Coupon($woocommerce->cart->get_applied_coupons()));
	$discountprice  =  $getDetailsCou->amount;
	$getDetails = ( new WC_Coupon($woocommerce->cart->get_applied_coupons()));
	$discount  =  $getDetails->code;
	$totalcart = WC()->cart->get_cart_contents_count();
	
	  
	$cart = $woocommerce->cart->get_cart();
	$count = 0;	
	foreach( $cart as $cart_item ){
		if (!empty($woocommerce->cart->applied_coupons)){
			if($cart_item['data']->get_price() > 1300 ){
				$count = $count + $cart_item['quantity']*80;	
			}	}
	}
	$totaldiscount= $discountprice * $totalcart + $count;
	
		//return 'Your code has been applied'." ".$discount;
	return 'Your code has been applied'." ".$discount." ". 'and you save RS:'." ". - $totaldiscount;
}




//perfect foreach loop add and condition
// add_action( 'woocommerce_cart_totals_after_order_total', 'bbloomer_wc_discount_total_30', 99);
// function bbloomer_wc_discount_total_30() {  
// 	global $woocommerce;
// 	$cart = $woocommerce->cart->get_cart();
// 	$count = 0;	
// 	foreach( $cart as $cart_item ){
// 		if (!empty($woocommerce->cart->applied_coupons)){
// 			if($cart_item['data']->get_price() > 1300 ){
// 				$count = $count + $cart_item['quantity'];	
// 				echo $count;
// 				echo '<br/>';

// 			}	
// 		}
// 	}
// }

add_action( 'woocommerce_cart_calculate_fees', 'prefix_update_cart_total' );
function prefix_update_cart_total(){
	global $woocommerce;    
	$cart = $woocommerce->cart->get_cart();
	$count = 0;	
	foreach( $cart as $cart_item ){
		if (!empty($woocommerce->cart->applied_coupons)){
			if($cart_item['data']->get_price() > 1300 ){
				$count = $count + $cart_item['quantity']*80;	
			}	}
	}
	
 $woocommerce->cart->add_fee( __( 'Down Payment', 'yourtext-domain' ) , -$count );
	//$total_price = $woocommerce->cart->subtotal - $count;
	//echo $total_price;
}



//Live

add_filter( 'woocommerce_cart_totals_coupon_label', 'label_hide_coupon_code_custom', 99, 2 );
function label_hide_coupon_code_custom(  $label, $coupon ) {
	global $woocommerce;  
	//$coupon_amount = WC_Coupon()->cart->get_coupon_discount_amount();
	$getDetailsCou = ( new WC_Coupon($woocommerce->cart->get_applied_coupons()));
	$discountprice  =  $getDetailsCou->amount;
	$getDetails = ( new WC_Coupon($woocommerce->cart->get_applied_coupons()));
	$discount  =  $getDetails->code;
	$totalcart = WC()->cart->get_cart_contents_count();

// 	$cart = $woocommerce->cart->get_cart();
// 	$count = 0;	
// 	foreach( $cart as $cart_item ){
// 		if (!empty($woocommerce->cart->applied_coupons)){
// 			if($cart_item['data']->get_price() >= 1250 ){
// 				$count = $count + $cart_item['quantity']*80;	
// 			}	
// 		}
// 	}

	$totaldiscount= $discountprice * $totalcart /*+ $count*/;
	return 'Your code has been applied'." ".  '<span>'.$discount.'</span>'    ." ". 'and you save RS:'." ". - $totaldiscount;
}

// add_action( 'woocommerce_cart_totals_after_order_total', 'bbloomer_wc_discount_total_30', 99);
// function bbloomer_wc_discount_total_30() {  
// 	global $woocommerce;
// 	$cart = $woocommerce->cart->get_cart();
// 	$count = 0;	
// 	foreach( $cart as $cart_item ){
// 		if (!empty($woocommerce->cart->applied_coupons)){
// 			if($cart_item['data']->get_price() > 1300 ){
// 				$count = $count + $cart_item['quantity']*80 ;	
// 				echo "You Got a discount per item: ", $count;
// 				echo '<br/>';

// 			}	
// 		}
// 	}
// }


// add_action( 'woocommerce_cart_calculate_fees', 'prefix_update_cart_total' );
// function prefix_update_cart_total(){
// 	global $woocommerce;    
// 	$cart = $woocommerce->cart->get_cart();
// 	$count = 0;	
// 	foreach( $cart as $cart_item ){
// 		if (!empty($woocommerce->cart->applied_coupons)){
// 			if($cart_item['data']->get_price() >= 1250 ){
// 				$count = $count + $cart_item['quantity']*80;	
// 			}	
// 		}
// 	}
// 		if (!empty($woocommerce->cart->applied_coupons)){
// 	$woocommerce->cart->add_fee( __( 'Service Discount:  (Included in Coupon Discount)', 'yourtext-domain' ) , -$count);
// 		}
// 		}





//Multiple Discount by product category 
/*function filter_woocommerce_coupon_get_discount_amount_tyre( $round, $discounting_amount, $cart_item, $single, $coupon ){
	global $woocommerce;  
	$coupon_codes = array('cgc-1244 ','cgc-5e5793f075dc6');
	$product_category20 = array('tyre');
	$second_percentage = 1;

	if ( $coupon->is_type('fixed_product') && in_array( $coupon->get_code(), $coupon_codes ) ) {
		if( has_term( $product_category20, 'product_cat', $cart_item['product_id'] ) ){
			$original_coupon_amount = (float) $coupon->get_amount() + 120;
			$discount = $original_coupon_amount * $second_percentage ;	
			$round = $discount;
		}
	}
	return $round;
}
add_filter( 'woocommerce_coupon_get_discount_amount', 'filter_woocommerce_coupon_get_discount_amount_tyre', 10, 5 ); */
