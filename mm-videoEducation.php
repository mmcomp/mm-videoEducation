<?php
/**
 * Plugin Name: Video Education
 * Plugin URI: http://www.mirsamie.com/wordpress/mm-videoEducation.zip
 * Description: This a plugin for selling offline and online video Courses.
 * Version: 1.0
 * Author: Mehrdad Mirsamie & Hamed Shakery
 * Author URI: https://www.linkedin.com/in/mehrdad-mirsamie-718a8440/
 */

include_once 'models/base.php';
include_once 'models/mymodel.php';
include_once 'models/video_session.php';
include_once 'models/video_homework.php';
include_once 'models/video_user.php';
include_once 'models/video_chat.php';
include_once 'models/video_pay.php';
include_once 'models/video_pay_detail.php';
require_once 'include/admin.php';
require_once 'include/jdf.php';
require_once 'include/i2S.php';
include_once 'include/adobe.php';

 

//------------------ADMIN------------------
add_filter('product_type_options', 'mm_product_type_options');
add_filter( 'woocommerce_product_data_tabs', 'mm_woocommerce_product_data_tabs' );
add_filter( 'woocommerce_product_data_panels', 'mm_woocommerce_product_data_panels' );
add_action("wp_ajax_mm_save_video_class", "mm_save_video_class");
add_action("wp_ajax_mm_remove_video_class", "mm_remove_video_class");
add_action("wp_ajax_mm_add_video_class", "mm_add_video_class");
add_action("wp_ajax_mm_add_video_pay", "mm_add_video_pay");
add_action('woocommerce_process_product_meta_simple', 'mm_woocommerce_process_product_meta');
add_action('woocommerce_process_product_meta_variable', 'mm_woocommerce_process_product_meta');
//------------------USER-------------------
add_filter( 'woocommerce_get_item_data', 'mm_woocommerce_get_item_data', 10, 2 );
add_action("woocommerce_before_add_to_cart_button", "_mm_woocommerce_before_add_to_cart_button");
add_filter( 'woocommerce_add_cart_item_data', 'mm_woocommerce_add_cart_item_data', 10, 3 );//upadte price when click on add to cart
add_action( 'woocommerce_before_calculate_totals', 'mm_woocommerce_before_calculate_totals' );
add_action( 'woocommerce_checkout_create_order_line_item', 'mm_woocommerce_checkout_create_order_line_item',10,4);
add_action('woocommerce_after_add_to_cart_button','mm_woocommerce_after_add_to_cart_button');


add_filter( 'woocommerce_add_to_cart_validation', 'mm_woocommerce_add_to_cart_validation', 10, 5 );

// add_action( 'woocommerce_single_product_summary', 'mm_woocommerce_single_product_summary', 1 );
// add_action( 'woocommerce_checkout_process', 'mm_woocommerce_checkout_process');
add_action('woocommerce_thankyou', 'mm_woocommerce_thankyou', 10, 1);
/*
add_action( 'template_redirect', 'misha_redirect_depending_on_product_id' );
 
function misha_redirect_depending_on_product_id(){
 
	if( !is_wc_endpoint_url( 'order-received' ) || empty( $_GET['key'] ) ) {
		return;
	}
 
	$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
	$order = wc_get_order( $order_id );
 
  // var_dump($order->get_items());
  // exit;
	foreach( $order->get_items() as $item ) {
		if(get_post_meta($item['product_id'], '_is_video', true) == 'yes') {
      // var_dump($item->get_meta_data()[0]->get_data());
			// wp_redirect( 'http://google.com' );
			// exit;
		}
	}
 
}
*/
add_filter( 'woocommerce_product_tabs', 'mm_woocommerce_product_tabs' );
//---------ADD MENU---------------------------------

add_filter( 'woocommerce_account_menu_items', 'iconic_account_menu_items', 10, 1 );
add_action( 'init', 'iconic_add_my_account_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_list_endpoint', 'mm_woocommerce_account_mm_videoclass_list_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_live_endpoint', 'mm_woocommerce_account_mm_videoclass_live_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_mine_endpoint', 'mm_woocommerce_account_mm_videoclass_mine_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_session_endpoint', 'mm_woocommerce_account_mm_videoclass_session_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_sessiondetails_endpoint', 'mm_woocommerce_account_mm_videoclass_sessiondetails_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_play_endpoint', 'mm_woocommerce_account_mm_videoclass_play_endpoint' );
add_action( 'woocommerce_account_mm_videoclass_fullsession_endpoint', 'mm_woocommerce_account_mm_videoclass_fullsession_endpoint' );
add_action( 'woocommerce_account_apex_endpoint', 'mm_woocommerce_account_apex_endpoint' );
add_shortcode('mm_dashboard', 'mm_dashboard');
//\--------ADD MENU---------------------------------
//---------CHAT-------------------------------------
// wp_localize_script( 'custom-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

add_action("wp_ajax_frontend_action_without_file" , "mm_chat_add");
// add_action("wp_ajax_mm_ac_sess" , "mm_get_adobe_session");
// add_action("wp_ajax_nopriv_frontend_action_without_file" , "mm_chat_add");
