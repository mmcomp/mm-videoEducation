<?php

/**
 * Register and Enqueue Styles.
 */
function aref_admin_register_styles() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'aref-admin-bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), $theme_version );
	wp_enqueue_style( 'aref-admin-bootstrap-rtl-style', get_template_directory_uri() . '/assets/css/bootstrap.rtl.css', array(), $theme_version );
	wp_enqueue_style( 'aref-admin-fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.css', array(), $theme_version );

}
add_action( 'admin_enqueue_scripts', 'aref_admin_register_styles' );

add_action('admin_head', 'aref_admin_styles');
function aref_admin_styles() {
  echo '<style>
    body {
        text-align: right !important;
    }
  </style>';
}


/**
 * Register and Enqueue Scripts.
 */
function aref_admin_register_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script( 'aref-admin-bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.js', array(), $theme_version, false );
	wp_script_add_data( 'aref-admin-bootstrap-js', 'async', true );

}
add_action( 'admin_enqueue_scripts', 'aref_admin_register_scripts' );


// --------------------ADMIN----------------------------
function mm_product_type_options ($product_type_options) {
	global $post;
	$id = $post->ID;
  $product_type_options['video_class'] = array(
    'id' => '_is_video',
    'wrapper_class' => 'show_if_simple show_if_variable',
    'label' => __('کلاس مجازی', 'woocommerce'),
    'description' => __('این آیتم در قالب ویدئو آنلاین و یا آفلاین و به صورت جلسه ای قابل فروش خواهد بود.', 'woocommerce'),
    'default' => get_post_meta($id, '_is_video', true)
  );
  ?>
	<!-- TEMP -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link 
  rel="stylesheet"
  href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css"
  integrity="sha384-vus3nQHTD+5mpDiZ4rkEPlnkcyTP+49BhJ4wJeJunw06ZAp+wzzeBPUXr42fi8If"
  crossorigin="anonymous">	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
  <script>
    jQuery(document).ready(function ($) {
      $("#_is_video").change(function() {
        if($(this).prop("checked")) {
          $("li.videoclass_options").show();
        }else {
          $("li.videoclass_options").hide();
        }
        if($(this).prop("checked") && $("#_is_hamayesh").prop("checked")) {
          $(".show_if_hamayesh").hide();
          $("#_is_hamayesh").prop("checked", false)
        }
      });
			<?php if(get_post_meta($id, '_is_video', true)) { ?>
				$("li.videoclass_options").show();
			<?php }else { ?>
				$("li.videoclass_options").hide();
			<?php } ?>
      
    });
  </script>
  <?php
  return $product_type_options;
}

function mm_woocommerce_product_data_tabs( $tabs) {
	$tabs['videoclass'] = array(
		'label'		=> __( 'کلاس مجازی', 'woocommerce' ),
		'target'	=> 'videoclass_options',
		'class'		=> array( 'show_if_simple', 'show_if_variable'  ),

	);
	return $tabs;
}

function mm_woocommerce_product_data_panels() {
	global $post;

	$vs = new VideoSession();
	$registered = $vs->loadByItem($post->ID);
	?>
		<div id='videoclass_options' class='panel woocommerce_options_panel hidden'>
			<div class='options_group p-20'>
				<h3 class="text-center mb-20">تعریف جلسات</h3>
				<div class="row">
					<div class="col-3 mb-15">
						<input type="text" style="width: 100%;" class="" id="classname" placeholder="نام" />
					</div>
					<div class="col-3 mb-15">
						<input type="text" style="width: 100%;" class="" id="classstart_time" placeholder="شروع" />
					</div>
					<div class="col-3 mb-15">
						<input type="text" style="width: 100%;" class="" id="classend_time" placeholder="پایان" />
					</div>
					<div class="col-3 mb-15">
						<input type="text" style="width: 100%;" class="" id="from-date" placeholder="از" />
					</div>
					<div class="col-3 mb-15">
						<input type="text" style="width: 100%;" class="" id="to-date" placeholder="تا" />
					</div>
					<div class="col-3 mb-15">
						<select id="day" style="width: 100%; max-height: 40px !important;" class="form-control" multiple>
							<option disabled>روزها</option>
							<option value="saturday">شنبه</option>
							<option value="sunday">یکشنبه</option>
							<option value="monday">دوشنبه</option>
							<option value="tuesday">سه‌شنبه</option>
							<option value="wednesday">چهارشنبه</option>
							<option value="thursday">پنجشنبه</option>
							<option value="friday">جمعه</option>
						</select>
					</div>
					<div class="col-3 mb-15">
						<input type="number" style="width: 100%;" class="" id="total-price" placeholder="قیمت کل" />
					</div>
					<div class="col-3 mb-15">
						<input type="text" style="width: 100%;" class="" id="total_video_link" placeholder="لینک ویدئو" />
					</div>
					<div class="col-3 mb-15">
						<a href="#" class="btn btn-primary btn-sm" onclick="return addGroupSessions(<?php echo $post->ID; ?>);">
						ایجاد
						</a>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col text-center">ردیف</div>
					<div class="col text-center">نام</div>
					<div class="col text-center">تاریخ</div>
					<div class="col text-center">ساعت شروع</div>
					<div class="col text-center">ساعت پایان</div>
					<div class="col text-center">قیمت</div>
					<div class="col text-center">فایل</div>
					<div class="col text-center">#</div>
				</div>
				<hr/>	
				<?php foreach($registered as $i => $register) { ?>
				<?php $register->start_date = jdate("Y/m/d", strtotime($register->start_date), '', 'Asia/Tehran', 'fa'); ?>
				<div class="row">
					<div class="col text-center"><?php echo $i+1; ?></div>
					<div class="col text-center"><?php echo $register->name; ?></div>
					<div class="col text-center"><?php echo $register->start_date; ?></div>
					<div class="col text-center"><?php echo $register->start_time; ?></div>
					<div class="col text-center"><?php echo $register->end_time; ?></div>
					<div class="col text-center"><?php echo $register->price; ?></div>
					<?php if($register->file_path) { ?>
					<div class="col text-center"><a href="<?php echo $register->file_path; ?>">فایل</a></div>
					<?php }else { ?>
					<div class="col text-center"></div>
					<?php } ?>
					<div class="col text-center">
						<a href="#" onclick="return startEditClass(<?php echo $i; ?>);" class="btn btn-success btn-sm my-half">
						ویرایش
						</a>
						<a href="#" onclick="return removeClass(<?php echo $register->id; ?>);" class="btn btn-danger btn-sm my-half">
						حذف
						</a>
					</div>
				</div>
				<hr/>				
				<?php } ?>
				<div class="row align-items-center mb-15">
					<div class="col">اصلاح</div>
					<div class="col text-center">
						<input placeholder="نام" type="text" id="_video_class_name" style="width: 100%;" class=""  />
					</div>
					<div class="col text-center">
						<input placeholder="تاریخ" type="text" id="_video_class_start_date" style="width: 100%;" class=""  />
					</div>
					<div class="col text-center">
						<input placeholder="شروع" type="text" id="_video_class_start_time" style="width: 100%;" class=""  />
					</div>
					<div class="col text-center">
						<input placeholder="پایان" type="text" id="_video_class_end_time" style="width: 100%;" class=""  />
					</div>
					<div class="col text-center">
						<input placeholder="قیمت" type="text" id="_video_class_price" style="width: 100%;" class=""  />
					</div>
				</div>
				<div class="row align-items-center mb-15">
					<div class="col-2">نوع جلسه :</div>
					<div class="col-10">
						<select id="_video_class_session_type" class="w-100">
							<option value="online">آنلاین</option>
							<option value="offline">آفلاین</option>
						</select>
					</div>
				</div>
				<div class="row align-items-center mb-15">
					<div class="col-2">لینک ویدئو :</div>
					<div class="col-10">
						<input id="_video_class_video_link" type="text" style="width: 100%;" class="" placeholder="لینک اصلی">
					</div>
				</div>
				<div class="row align-items-center mb-15">
					<div class="col-2">
						آزمون : 					
					</div>
					<div class="col-10">
						<input id="_video_class_file_path" class="" type="file" />
					</div>
					<div class="col-12 text-left">
						<a onclick="editClass(<?php echo $post->ID; ?>);" class="btn btn-success btn-sm mt-15 text-white">ثبت</a>
					</div>
				</div>
			</div>

		</div>
	<script>
	let registers = <?php echo json_encode($registered); ?>;
	let selectedId = 0;
	function addGroupSessions(item_id) {
		if(!confirm('آیا ثبت انجام شود؟')) {
			return false;
		}
		
		var data = {
			"name": jQuery("#classname").val().trim(),
			"video_link": jQuery("#total_video_link").val().trim(),
			"start_time": jQuery("#classstart_time").val().trim(),
			"end_time": jQuery("#classend_time").val().trim(),
			"from-date": jQuery("#from-date").val().trim(),
			"to-date": jQuery("#to-date").val().trim(),
			"total-price": jQuery("#total-price").val().trim(),
			"day": [],
			item_id,
			"action": "mm_add_video_class",	
		};

		jQuery("#day").find('option:selected').each(function(id, field) {
			data.day.push(field.value);
		});
		console.log(data);

		jQuery.post(ajaxurl, data, function(result) {
			console.log(result);
			alert('ثبت با موفقیت انجام شد');
			jQuery("form#post").submit();
		}).fail(function() {
			alert('خطا در ثبت');
		});
		return false;
	}
	function editClass(itemId) {
		if(selectedId<=0) {
			alert('لطفا یک جلسه را برای اصلاح انتخاب کنید');
			return false;
		}
		var formData = new FormData();
		formData.append('id', selectedId);
		formData.append('item_id', itemId);
		formData.append('name', jQuery("#_video_class_name").val().trim());
		formData.append('start_date', jQuery("#_video_class_start_date").val().trim());
		formData.append('start_time', jQuery("#_video_class_start_time").val().trim());
		formData.append('end_time', jQuery("#_video_class_end_time").val().trim());
		formData.append('price', jQuery("#_video_class_price").val().trim());
		formData.append('session_type', jQuery("#_video_class_session_type").val().trim());
		formData.append('video_link', jQuery("#_video_class_video_link").val().trim());
		formData.append('action', "mm_save_video_class");
		if(jQuery("#_video_class_file_path")[0].files[0]) {
			formData.append('file_path', jQuery("#_video_class_file_path")[0].files[0]);
		}
		var request = new XMLHttpRequest();
		request.open("POST", ajaxurl);
		request.onload = function(inp) {
			console.log(inp);
			if(request.status==200) {
				alert('ثبت با موفقیت انجام شد');
				jQuery("form#post").submit();
			}else {
				alert('خطا در ثبت');
			}
		};
		request.onreadystatechange = function() {
			if (request.readyState == XMLHttpRequest.DONE) {
					console.log(request.responseText);
			}
		}
		request.send(formData);
	}
	function removeClass(registerId) {
		if(!confirm('آیا حذف شود؟')){
			return false;
		}
		data = {
			id: registerId,
			action: "mm_remove_video_class",
		}
		console.log(ajaxurl, data);
		jQuery.post(ajaxurl, data, function(res) {
			console.log(res);
			try{
				res = JSON.parse(res);
			}catch(e){
				console.log('JSON_ERROR:', e);
				return alert('خطا در حذف');
			}
			console.log(res);
			if(res.status && res.status==1) {
				alert('حذف با موفقیت انجام شد');
				return jQuery("form#post").submit();
			}else {
				return alert('خطا در حذف');
			}
		}).fail(function() {
			return alert('خطا در برقراری ارتباط');
		});
		return false;
	}
	function startEditClass(i) {
		console.log(registers[i]);
		selectedId = registers[i].id;
		jQuery("#_video_class_name").val(registers[i].name);
		jQuery("#_video_class_start_date").val(registers[i].start_date);
		jQuery("#_video_class_start_time").val(registers[i].start_time);
		jQuery("#_video_class_end_time").val(registers[i].end_time);
		jQuery("#_video_class_price").val(registers[i].price);
		jQuery("#_video_class_session_type").val(registers[i].session_type);
		jQuery("#_video_class_video_link").val(registers[i].video_link);
		return false;
	}
	</script>
	<?php
}

function mm_persian_to_english($inp) {
	$inp = str_replace('۰', '0', $inp);
	$inp = str_replace('۱', '1', $inp);
	$inp = str_replace('۲', '2', $inp);
	$inp = str_replace('۳', '3', $inp);
	$inp = str_replace('۴', '4', $inp);
	$inp = str_replace('۵', '5', $inp);
	$inp = str_replace('۶', '6', $inp);
	$inp = str_replace('۷', '7', $inp);
	$inp = str_replace('۸', '8', $inp);
	$inp = str_replace('۹', '9', $inp);
	return $inp;
}

function mm_jalali_to_geregorian($inp) {
	$inp = explode('/', mm_persian_to_english($inp));
	if(count($inp)!=3) {
		return null;
	}
	if((int)$inp[0]>(int)$inp[2]) {
		$tmp = jalali_to_gregorian((int)$inp[0], (int)$inp[1], (int)$inp[2]);
	}else {
		$tmp = jalali_to_gregorian((int)$inp[2], (int)$inp[1], (int)$inp[0]);
	}
	return $tmp[0].'-'.$tmp[1].'-'.$tmp[2];
}

function fixTime($inp) {
	$tmp = explode(':', $inp);
	if(count($tmp)<=1) {
		$inp = "{$inp}:00:00";
	}else if(count($tmp)==2) {
		$inp = "{$inp}:00";
	}else if(count($tmp)>3) {
		$inp = "{$tmp[0]}:{$tmp[1]}:{$tmp[2]}";
	}
	return $inp;
}

function mm_add_video_class() {
	$out = [
		"status"=>0,
		"data"=>null,
	];
	$fromDate = mm_jalali_to_geregorian($_REQUEST['from-date']);
	$toDate = mm_jalali_to_geregorian($_REQUEST['to-date']);
	// echo "From : $fromDate\n";
	// echo "To : $toDate\n";
	if(strtotime($toDate)<strtotime($fromDate)) {
		die(json_encode($out, true));
	}
	$currentDate = $fromDate;
	$vs = new VideoSession();
	$selectedDates = [];
	while(strtotime($currentDate)<=strtotime($toDate)) {
		// echo $currentDate . " ";
		$dayOfWeek = strtolower(date("l", strtotime($currentDate)));
		// echo $dayOfWeek . "\n";
		if(in_array($dayOfWeek, $_REQUEST['day'])) {
			$selectedDates[] = $currentDate;
		}
		$currentDate = date("Y-m-d", strtotime($currentDate . ' + 1 day'));
	}
	$ids = [];
	$str = new convert ;
	// echo "start adding\n";
	// var_dump($selectedDates);
	foreach($selectedDates as $index => $selectedDate) {
		$t_price = $index+1 ;
		if($t_price!=3) {
			$t_price_str = $str->finalcalc($t_price) . 'م';
		}else {
			$t_price_str = 'سوم';
		}

		$data = [
			'item_id'=>$_REQUEST['item_id'],
			'name'=>$_REQUEST['name'] . ' ' . $t_price_str,
			'start_date'=>$selectedDate,
			'start_time'=>fixTime($_REQUEST['start_time']),
			'end_time'=>fixTime($_REQUEST['end_time']),
			'price'=>ceil($_REQUEST['total-price']/count($selectedDates)),
			'video_link'=>$_REQUEST['video_link'],
		];
		// echo "insert\n";
		$ids[] = $vs->insert($data);
		$out['status'] = 1;
	}
	
	$out['data'] = $ids;

	die(json_encode($out, true));
}

function mm_save_video_class() {
	$data = [
		'item_id'=>$_REQUEST['item_id'],
		'name'=>$_REQUEST['name'],
		'start_date'=>mm_jalali_to_geregorian($_REQUEST['start_date']),
		'start_time'=>$_REQUEST['start_time'],
		'end_time'=>$_REQUEST['end_time'],
		'price'=>(int)$_REQUEST['price'],
		'session_type'=>$_REQUEST['session_type'],
		'video_link'=>$_REQUEST['video_link'],
	];
	if($_FILES['file_path']) {
		$fileName = strtotime(date("Y-m-d")) . '.pdf';
		if(move_uploaded_file($_FILES["file_path"]["tmp_name"], wp_get_upload_dir()['path'] . $fileName)) {
			$data['file_path'] = wp_get_upload_dir()['url'] . $fileName;
		}
	}
	$out = [
		"status"=>0,
		"data"=>null,
	];
	// var_dump($data);
	// var_dump($_REQUEST['id']);
	$vs = new VideoSession();
	if($vs->update($data, $_REQUEST['id'])) {
		$data['id'] = $_REQUEST['id'];
		$out['status'] = 1;
		$out['data'] = $data;
	}
	die(json_encode($out, true));
}

function mm_remove_video_class() {
	$id = $_REQUEST['id'];
	$out = [
		"status"=>0,
		"data"=>null,
	];
	$vs = new VideoSession($id);
	$newVs = $vs->delete();
	if($newVs) {
		$out['status'] = 1;
	}
	die(json_encode($out, true));
}

function mm_woocommerce_process_product_meta($post_id) {
	$_is_video = isset($_POST['_is_video']) ? 'yes' : 'no';
	update_post_meta($post_id, '_is_video', $_is_video);
}
//------------------USER------------------------------
function mm_woocommerce_get_item_data($cart_data, $cart_item) {
	$custom_items = array();
	if(!empty($cart_data)) {
		$custom_items = $cart_data;
	}

	$product_id = $cart_item['product_id'];
	if(get_post_meta($product_id, '_is_video', true) == 'yes' && isset($cart_item['video_sessions'])){
		$vs = new VideoSession();
		$custom_items[] = array(
				'name'      => __( 'جلسه', 'woocommerce' ),
				'value'     => $cart_item['video_sessions'],
				'display'   => implode(' , ',$vs->idToNames($cart_item['video_sessions']))
		);
	}
	return $custom_items;
}

function removeSeconds($inp) {
	$inp = explode(':', $inp);
	if(count($inp)>=2) {
		$inp = $inp[0] . ':' . $inp[1];
	}else {
		$inp = $inp[1];
	}
	return $inp;
}

function mm_woocommerce_before_add_to_cart_button() {
	$out = '';
	$id = get_the_ID();
	$vs = new VideoSession();
	$sessions = $vs->loadByItem($id);

	if (get_post_meta($id, '_is_video', true) == 'yes') { ?>
	<div class="mm-video-div">
		<h5 style="text-align: center;"> انتخاب جلسه</h5>
		انتخاب <a href="#" onclick="mm_selectAll();return false;">همه</a> یا <a href="#" onclick="mm_unSelectAll();return false;">هیچ</a>
		<?php foreach ($sessions as $session) { ?>
		<div class="mm-session-selection">
			<input type="checkbox" class="_video_sessions" name="video_sessions[]" id="_video_session_<?php echo $session->id; ?>" value="<?php echo $session->id; ?>" data-price="<?php echo $session->price; ?>" />
			<?php echo $session->name . '[' . jdate('Y/m/d', strtotime($session->start_date)) . ' ' . removeSeconds($session->start_time) . '-' . removeSeconds($session->end_time) .  '] ' . number_format($session->price) . '﷼'; ?>
		</div>
		<?php } ?>
	</div> 
	<div class="mm-video-div-msg" ></div>
	<script>
		var priceThem = `<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">تومان</span>#amount#</span>`;
		var totalPrice = 0;
    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
		function mm_selectAll() {
			jQuery("input._video_sessions").each(function(id, field) {
				if(jQuery(field).prop('checked')==false) {
					jQuery(field).prop('checked', true);
					jQuery(field).trigger('change');
				}
			});
		}
		function mm_unSelectAll() {
			jQuery("input._video_sessions").each(function(id, field) {
				if(jQuery(field).prop('checked')==true) {
					jQuery(field).prop('checked', false);
					jQuery(field).trigger('change');
				}
			});
		}
		jQuery(document).ready(function() {
			jQuery('.quantity').hide();
			jQuery('button[name*=add-to-cart]').on('click',function(event) {
				if(jQuery("input._video_sessions:checked").length==0) {
					event.preventDefault();
					jQuery('.mm-video-div-msg').html('لطفا حداقل یک جلسه انتخاب نمایید');
				} else {
					jQuery(this).trigger('click');
				}
			});
			jQuery("input._video_sessions").change(function() {
				console.log(jQuery(this).data('price'));
				if(jQuery(this).prop('checked')) {
					totalPrice += jQuery(this).data('price');
				}else {
					totalPrice -= jQuery(this).data('price');
				}
				if(totalPrice>0) {
					jQuery('.price').html(priceThem.replace(/#amount#/g, numberWithCommas(totalPrice)));
				}else {
					jQuery('.price').html('');
				}
			});
			mm_selectAll();
		});
	</script>
	<style>
			.mm-video-div-msg{
				margin: 10px 0 10px 0 ;
				color: red;
				font-weight:bold;
			}
			.mm-session-selection{
				padding: 5px;
				border: 1px solid #eaeaea;
				margin: 3px;
				background-color: rgb(122, 221, 232);
			}
	</style>
	<?php
	//echo coupon_class::getCouponCode(1200);
	}else {
    ?>
  <script>
    jQuery('#tab-desc_tab').remove();
    jQuery("#tab-title-desc_tab").remove();
    jQuery("#tab-title-description").addClass('active');
    jQuery("#tab-description").show();
  </script>
    <?php
  }
}

function mm_get_video_sessionPrice($video_sessions) {
	$vs = new VideoSession();
	return $vs->sumPrice($video_sessions);
}

function mm_woocommerce_add_cart_item_data($cart_item_data, $product_id, $variation_id){
	if(get_post_meta($product_id, '_is_video', true) == 'yes'){
		if(isset($_POST['video_sessions'])) {
			$cart_item_data['video_sessions'] = $_POST['video_sessions'];
			$cart_item_data['warranty_price'] = mm_get_video_sessionPrice($_POST['video_sessions']);
		}else {
			$vs = new VideoSession();
			$cart_item_data['warranty_price'] = mm_get_video_sessionPrice($vs->loadByItemIds($product_id));
		}
	}
	return $cart_item_data;
}

function mm_woocommerce_before_calculate_totals($cart_object){
	foreach ( $cart_object->get_cart() as $key => $value ) {
			if( isset( $value['warranty_price'] ) ) {
					$price = $value['warranty_price'];
					$value['data']->set_price($price);
			}
	}
}

function mm_woocommerce_checkout_create_order_line_item($item, $cart_item_key, $values, $order ) {
	if(isset($values['video_sessions'])){
		$item->update_meta_data('video_sessions', implode(',', $values['video_sessions']));
		$item->add_meta_data('_cart_item_key', $cart_item_key);
	}
}

function _mm_woocommerce_thankyou() {
	if ( ! $order_id )
	return;
	$vs = new VideoSession();
	$order = wc_get_order( $order_id );
	foreach ( $order->get_items() as $item_id => $item ) {
		if( $item['variation_id'] > 0 ){
			$product_id = $item['variation_id'];
		} else {
			$product_id = $item['product_id'];
		}
		$sms_sent = (int)get_post_meta($order_id, '_vsmsSent', true)==1;
		if(!$sms_sent){
				$is_video = get_post_meta($product_id, '_is_video', true);
				if($is_video) {

				}
				$tarikh = jdate("Y/m/d",strtotime(get_post_meta($product_id, '_tarikh')[0]));
				$addr = get_post_meta($product_id, '_address', true);
				$time = get_post_meta($product_id, '_time', true);
				//$current_user = wp_get_current_user();
				if($is_hamayesh=='yes'){
						$hamayesh_name = get_the_title($product_id);
						$item_meta= wc_get_order_item_meta($item_id,'chairs');
						$sms_text = '
						سلام،
						کاربر عزیز 
						'.get_post_meta($order_id, '_billing_first_name', true). ' '.get_post_meta($order_id, '_billing_last_name', true).'
						ثبت نام شما در 
						'.$hamayesh_name .'
						در تاریخ: 
						'.$tarikh.'
						ساعت:
						'.$time.'
						با موفقیت انجام شد. در ضمن شماره/های صندلی شما
						'.$item_meta.' 
						می باشد
						لطفا قبل از شروع همایش در محل سالن همایش:
						'.$addr.'
						حضور داشته باشید
						با احترام خانه کنکور عارف
										';
				}
				$mobile = get_post_meta($order_id, '_billing_phone', true);
				//echo $mobile;
				$sent =SmsHelper::send($mobile, trim(preg_replace('/\s+/', ' ', $sms_text)));
				if($sent){
						
						update_post_meta($order_id, '_smsSent',1);
				}
		}
	}
}

function mm_woocommerce_thankyou($order_id) {
	$order = wc_get_order( $order_id );
	$orderItems = $order->get_items();
	$vu = new VideoUser;
	$vs = new VideoSession;
	$adobeConnect = new AdobeConnect("saied.banuie@gmail.com", "Banuie@159951");
	foreach($orderItems as $orderItem) {
		$product_id = version_compare( WC_VERSION, '3.0', '<' ) ? $orderItem['product_id'] : $orderItem->get_product_id();
		$custom_field = get_post_meta( $product_id, '_is_video', true);
		if($custom_field=='yes') {
			$user = $vu->addUser();
			$data = $orderItem->get_formatted_meta_data();
			$video_sessions = [];
			foreach($data as $meta) {
				if($meta->key == 'video_sessions') {
					$video_sessions = explode(',', $meta->value);
				}
			}
			if(count($video_sessions)==0) {
				$videoSessions = $vs->loadByItem($product_id);
			}else {
				$videoSessions = $vs->loadByIds($video_sessions);
			}
			foreach($videoSessions as $videoSession) {
				$adobeConnect->addUserMeeting($vu->principal_id, $videoSession->sco_id);
			}
		}
	}
	?>
	<style>
		.woocommerce-order-details {
			display: none !important;
		}
	</style>
	<a href="<?php echo site_url('/my-account/'); ?>"><h1>بازگشت</h1></a>
	<script>
		// jQuery('document').ready()
	</script>
	<?php
}

/*

function mm_woocommerce_checkout_process(){
	$vs = new VideoSession();
	$items = WC()->cart->get_cart();
	$notAlow=[];
	$msg = '';
	foreach ($items as $item => $values)
	{
			$product_id = $values['product_id'];
			$name = $values['data']->post->post_title;
			$sessions = $vs->loadByItemIds($product_id);
			foreach($values['video_sessions'] as $number){
					if(in_array($number,$sessions)){
							$notAlow[] = $number;
					}
			}
			if(count($notAlow)>0){
					$msg .='متاسفانه شماره صندلی/های  '.implode(',',$notAlow).' از '.$name.' در همین لحظه توسط شخص دیگری رزرو شد لطفا به 
					<a style="color:yellow" href="'.get_site_url().'/cart" >
					سبد خرید
					</a>
					 خود بازگردید و این آیتم را حذف کنیدو مجدد صندلی دیگری انتخاب کنید.'.'<br/>';
			}
	}
	if($msg!=''){
			wc_add_notice($msg, 'error' );
	}
}


function mm_woocommerce_single_product_summary() {
	global $product;
	global $woocommerce;
	$items = $woocommerce->cart->get_cart();
	$product_id = get_the_ID();
	$tarikh = get_post_meta($product_id,'_tarikh',true);
	$time = get_post_meta($product_id,'_time',true);
	$date_tmp = strtotime($tarikh.' '.$time);
	$now = strtotime(date("Y-m-d H:i:s"));
	if( $date_tmp < $now){
			$notice ='به علت پایان مهلت ثبت نام امکان ثبت نام وجود ندارد';
			wc_print_notice( $notice, 'notice' );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			return;
	}
	$in_cart = false;
	foreach( $items as $cart_item ) {
			$product_in_cart = $cart_item['product_id'];
			if ( $product_in_cart === $product_id ) $in_cart = true;
	 }
		
			 if ( $in_cart ) {
		
					 $notice ='این همایش به سبد خرید افزوده شده است امکان سفارش مجدد وجود ندارد';
					 wc_print_notice( $notice, 'notice' );
		
			 }
		
	
	//var_dump($items);
	// For simple product types
	if( $product->is_type( 'simple' ) && get_post_meta($product_id, '_is_hamayesh', true)=='yes' && $in_cart ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}

}

*/

function _getVideoClasses( $product_id, $order_status = ['wc-completed','wc-processing','wc-pending']  ){
	global $wpdb;
	$out=[];
	$vs = new VideoSession();
	$q = $vs->query("
			SELECT order_items.order_item_id 
			FROM #PRE#woocommerce_order_items as order_items
			LEFT JOIN #PRE#woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
			LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
			WHERE posts.post_type = 'shop_order'
			AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
			AND order_items.order_item_type = 'line_item'
			AND order_item_meta.meta_key = '_product_id'
			AND order_item_meta.meta_value = '$product_id'
			group by order_items.order_item_id
	");
	
	$tmp = '';
	foreach($q as $r){
			$p = $vs->query("
			select meta_value 
			FROM #PRE#woocommerce_order_itemmeta as order_items
			where meta_key = 'video_sessions' 
			AND order_item_id=".$r->order_item_id);
			if(isset($p[0])) {
				$tmp .= ($tmp==''?'':',').$p[0]->meta_value;
			}
			$p=null;
	}
	$out = explode(',',$tmp);
	sort($out);
	return $out;
}

function getVideoClasses( $product_id, $order_status = ['wc-completed','wc-processing','wc-pending'] ) {
	$vs = new VideoSession;
	$customer_orders = get_posts(array(
		'numberposts' => -1,
		'meta_key' => '_customer_user',
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_value' => get_current_user_id(),
		'post_type' => wc_get_order_types(),
		'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
	));

	$sessions = [];
	foreach ($customer_orders as $customer_order) {
		$sql = "SELECT meta_key, meta_value FROM #PRE#woocommerce_order_itemmeta LEFT JOIN #PRE#woocommerce_order_items ON (#PRE#woocommerce_order_items.order_item_id=#PRE#woocommerce_order_itemmeta.order_item_id) WHERE order_id = {$customer_order->ID}";
		$orderItems = $vs->query($sql);
		$video_sessions = '';
		$theproduct_id = 0;
		foreach($orderItems as $orderItem) {
			if($orderItem->meta_key=='video_sessions') {
				$video_sessions = trim($orderItem->meta_value);
			}else if($orderItem->meta_key=='_product_id') {
				$theproduct_id = (int)$orderItem->meta_value;
			}
		}
		if($theproduct_id==$product_id) {
			if($video_sessions!='') {
				return explode(',', $video_sessions);
			}
			$sql = "SELECT * FROM #PRE#video_session WHERE item_id = {$product_id}";
			$vSessions = $vs->query($sql);
			foreach($vSessions as $vSession) {
				$sessions[] = $vSession->id;
			}
			return $sessions;
		}
	}
}

function mm_woocommerce_after_add_to_cart_button(){
	$product_id = get_the_ID();
	
	$vs = new VideoSession();
	$sessions= getVideoClasses($product_id);
	$sessionDatas = $vs->idToNames($sessions);
	if(get_post_meta($product_id, '_is_video', true) == 'yes'){
		?>
			<div class='video_sessions' >
					<span>
							جلسات خریداری شده تا کنون:
					</span>
					<div>
						<?php
						if(isset($sessionDatas[0])) {
						foreach($sessionDatas as $sessionName) {
							?>
							<span><?php echo $sessionName; ?></span>
							<?php
						}
						}
						?>
					</div>
			</div>
			<style>
			.video_sessions{
					clear:both;
					padding-top:10px;
			}
			</style>
		<?php
	}
}

function mm_woocommerce_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
	global $woocommerce;
	$vs = new VideoSession;
	$vu = new VideoUser;
	$myClassSessions = $vs->loadMyClassSessions();
	$items = $woocommerce->cart->get_cart();
	if(get_post_meta($product_id, '_is_video', true) == 'yes'){
		// var_dump($vu->addUser());
		foreach($myClassSessions as $_product_id=>$selectedSessions) {
			if($_product_id == $product_id){
				$newSessions = $_REQUEST['video_sessions'];
				if(!isset($selectedSessions[0])) {
					wc_add_notice( __( 'این کلاس قبلا بطور کامل خرید شده است', 'textdomain' ), 'error' );
					return false;
				}

				$new = false;
				foreach($newSessions as $newSession) {
					if(!in_array($newSession, $selectedSessions)) {
						$selectedSessions[] = $newSession;
						$new = true;
					}
				}

				if(!$new) {
					wc_add_notice( __( 'جلسه های مربوطه در این کلاس قبلا خرید شده اند', 'textdomain' ), 'error' );
					return false;
				}

				$new = false;
				$cart = WC()->cart->cart_contents;

				foreach($cart as $cart_item_id=>$cart_item) {
					if($cart_item['product_id']==$product_id && isset($cart_item['video_sessions']) && !$new) {
						$cart_item['video_sessions'] = $selectedSessions;
						$cart_item['warranty_price'] = mm_get_video_sessionPrice($selectedSessions);
						WC()->cart->cart_contents[$cart_item_id] = $cart_item;
						$new = true;
					}
				}

				if(!$new) {
					wc_add_notice( __( 'جلسه های مربوطه در این کلاس قبلا خرید شده اند', 'textdomain' ), 'error' );
					return false;
				}

				WC()->cart->set_session();
				wc_add_notice( __( 'جلسه های مربوطه به سبد خرید افزوده شد', 'textdomain' ) );
				return false;
			}
		}
		foreach($items as $item => $values) { 
			$_product_id = $values['data']->get_id();
			if($_product_id == $product_id){
				$selectedSessions = isset($values['video_sessions'])? $values['video_sessions']: null;
				if($selectedSessions==null) {
					wc_add_notice( __( 'این کلاس قبلا بطور کامل ثبت نام شده است', 'textdomain' ), 'error' );
					return false;
				}
				$newSessions = $_REQUEST['video_sessions'];
				$new = false;
				foreach($newSessions as $newSession) {
					if(!in_array($newSession, $selectedSessions)) {
						$selectedSessions[] = $newSession;
						$new = true;
					}
				}
				if(!$new) {
					wc_add_notice( __( 'جلسه های مربوطه در این کلاس قبلا ثبت نام شده اند', 'textdomain' ), 'error' );
					return false;
				}

				$new = false;
				$cart = WC()->cart->cart_contents;

				foreach($cart as $cart_item_id=>$cart_item) {
					if($cart_item['product_id']==$product_id && isset($cart_item['video_sessions']) && !$new) {
						$cart_item['video_sessions'] = $selectedSessions;
						$cart_item['warranty_price'] = mm_get_video_sessionPrice($selectedSessions);
						WC()->cart->cart_contents[$cart_item_id] = $cart_item;
						$new = true;
					}
				}

				if(!$new) {
					wc_add_notice( __( 'جلسه های مربوطه در این کلاس قبلا انتخاب شده اند', 'textdomain' ), 'error' );
					return false;
				}

				WC()->cart->set_session();
				wc_add_notice( __( 'جلسه های مربوطه به سبد خرید افزوده شد', 'textdomain' ) );
				return false;
			}
		}
	}

  return true;
}

function mm_woocommerce_product_tabs( $tabs ) {
// Adds the new tab
    $tabs['desc_tab'] = array(
        'title'     => __( 'جلسات', 'woocommerce' ),
        'priority'  => 5,
        'callback'  => 'mm_woo_new_product_tab_content'
    );
  return $tabs;
}

function mm_woo_new_product_tab_content() {
  // The new tab content
//   echo '<p>Lorem Ipsum</p>';
  mm_woocommerce_before_add_to_cart_button();
}
//---------ADD MENU---------------------------------

/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */
function iconic_account_menu_items( $items ) {
	$currentItems = $items;
	$items = [];
	$items['dashboard'] = __( 'میزکار', 'iconic' );
	$items['mm_videoclass_list'] = __( 'کلاس های آنلاین(محصولات)', 'iconic' );
	$items['mm_videoclass_mine'] = __( 'دوره های کامل من', 'iconic' );
	$items['mm_videoclass_session'] = __( 'دوره های تک جلسه من', 'iconic' );
	foreach($currentItems as $key => $value) {
		$items[$key] = $value;
	}
	return $items;
}

/**
 * Add endpoint
 */
function iconic_add_my_account_endpoint() {
	add_rewrite_endpoint( 'mm_videoclass_list', EP_PAGES );
	add_rewrite_endpoint( 'mm_videoclass_mine', EP_PAGES );
	add_rewrite_endpoint( 'mm_videoclass_session', EP_PAGES );
	add_rewrite_endpoint( 'mm_videoclass_sessiondetails', EP_PAGES );
	add_rewrite_endpoint( 'mm_videoclass_play', EP_PAGES );
}

/**
 * Information content
 */
function mm_woocommerce_account_mm_videoclass_list_endpoint() {
	$vs = new VideoSession();
	$results = $vs->loadClasses();
	$categories = $vs->loadCatalgories();
	?>
	<!-- TEMP -->
	<!-- <script src="https://kit.fontawesome.com/c9fca311fa.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
	<div class="content">
		<div class="row">
			<div class="col text-center">
				<h1>
				کلاس های مجازی موجود
				</h1>
			</div>
		</div>
		<div class="row border pt-0 p-3">
			<div class="col-12">
				<h3 class="text-center">
				جستجو براساس پایه و درس
				</h3>
			</div>
			<?php 
				foreach($categories as $category) { 
					$subCats = $vs->loadCatalgories($category->term_id);
					if(count($subCats)>0) {
			?>
			<div class="col-4 text-right">
				<label>
				<?php echo $category->name; ?> :
				</label>
				<select class="form-control mm-video-class-cat" onchange="mm_refresh_video_classes();" >
					<option>همه <?php echo $category->name; ?></option>
					<?php $subCats = $vs->loadCatalgories($category->term_id); ?>
					<?php foreach($subCats as $scat) { ?>
					<option value="<?php echo $scat->term_id; ?>"><?php echo $scat->name; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php }} ?>
		</div>
		<div class="row border mt-3 rounded">
			<?php foreach($results as $result) { ?>
			<div class="col-3 border m-3 p-0 pb-3 mm-video-class-objects rounded" data-categories="<?php echo (count($result->cats)>0)?implode(',', $result->cats):''; ?>">
				<?php //echo $result->post_content; ?>
				<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?class_id=' . $result->id); ?>">
				<p class="text-center">
          <?php if($result->image_url) { ?>
					<img class="rounded" src="<?php echo $result->image_url; ?>" /><br/>
          <?php }else{ ?>
          <img class="rounded" src="<?php echo site_url('/wp-content/plugins/mm-videoeducation/include/12.png'); ?>" /><br/>
          <?php } ?>
					<?php echo urldecode($result->post_name); ?>
				</p>
				</a>
				<?php if($result->mine==false) { ?>
				<div class="text-center border border-success rounded" style="border-radius: 20px;">
					<?php echo number_format($result->price); ?> تومان
				</div>
				<br/>
				<a href="/my-account/mm_videoclass_list/?add-to-cart=<?php echo $result->id; ?>" class="btn btn-success btn-sm btn-block" ><i class="fa fa-shopping-cart"></i> افزودن به سبد خرید</a>
				<a href="/product/<?php echo $result->post_name; ?>" class="btn btn-primary btn-sm btn-block" >ثبت نام جداگانه هر جلسه</a>
				<a href="<?php echo site_url('/product/' . $result->post_name); ?>" class="btn btn-warning btn-sm btn-block" >جزئیات بیشتر</a>				
				<?php } else { ?>
				<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?class_id=' . $result->id); ?>" class="btn btn-warning btn-sm btn-block" >جزئیات بیشتر</a>				
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
	<script>
		function mm_refresh_video_classes() {
			let cats = [];
			jQuery("select.mm-video-class-cat option:selected").each(function(id, field) {
				if(!isNaN(parseInt(jQuery(field).val(), 10))) {
					cats.push(jQuery(field).val());
				}
			});
			jQuery("div.mm-video-class-objects").hide();
			jQuery("div.mm-video-class-objects").each(function(id, field) {
				let blockCats = jQuery(field).data('categories').split(',');
				let showThis = false;
				for(let cat of cats) {
					if(jQuery.inArray(String(cat), blockCats)>=0) {
						showThis = true;
					}
				}
				if(showThis || cats.length==0) {
					jQuery(field).show();
				}
			});
		}
	</script>
	<?php
}

function _mm_woocommerce_account_mm_videoclass_mine_endpoint() {
	$homeworkUploaded = false;
	if(isset($_POST['video_session_id'])) {
		$vh = new VideoHomework();
		$data = [
			'video_session_id'=>$_POST['video_session_id'],
		];
		if(isset($_FILES['file_path'])) {
			$name = $_FILES['file_path']['name'];
			$ext = explode(".", $name);
			$ext = $ext[count($ext)-1];
			$fileName = strtotime(date("Y-m-d")) . '.' . $ext;
			if(!in_array(strtolower($ext), ['php', 'js', 'htm', 'html', 'css'])) {
				if(move_uploaded_file($_FILES["file_path"]["tmp_name"], wp_get_upload_dir()['path'] . $fileName)) {
					$data['file_path'] = wp_get_upload_dir()['url'] . $fileName;
					$data['id'] = $vh->insert($data);
					$homeworkUploaded = true;
				}
			}
		}
	}
	$vs = new VideoSession();
	$allResults = $vs->loadMineClasses();
	$results = $allResults["classes"];
	$mineSessions = $allResults['sessions'];
	$categories = $vs->loadCatalgories();
	?>
	<!-- TEMP -->
	<!-- <script src="https://kit.fontawesome.com/c9fca311fa.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
	<div class="content">
		<div class="row">
			<div class="col text-center">
				<h1>
				کلاس های مجازی من
				</h1>
			</div>
		</div>
		<div class="row border pt-0 p-3">
			<div class="col-12">
				<h3 class="text-center">
				جستجو براساس پایه و درس
				</h3>
			</div>
			<?php 
				foreach($categories as $category) { 
					$subCats = $vs->loadCatalgories($category->term_id);
					if(count($subCats)>0) {
			?>
			<div class="col-4 text-right">
				<label>
				<?php echo $category->name; ?> :
				</label>
				<select class="form-control mm-video-class-cat" onchange="mm_refresh_video_classes();" >
					<option>همه <?php echo $category->name; ?></option>
					<?php $subCats = $vs->loadCatalgories($category->term_id); ?>
					<?php foreach($subCats as $scat) { ?>
					<option value="<?php echo $scat->term_id; ?>"><?php echo $scat->name; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php }} ?>
		</div>
		<div class="row border mt-3">
			<?php foreach($results as $result) { ?>
			<div class="col-3 border m-3 p-0 pb-3 mm-video-class-objects" data-categories="<?php echo (count($result->cats)>0)?implode(',', $result->cats):''; ?>">
				<?php echo $result->post_content; ?>
				<?php $sessions = $vs->loadByIds($mineSessions[$result->id]); ?>
				<?php foreach($sessions as $session) { ?>
				<div class="alert 
				<?php echo (strtotime($session->start_date)>=strtotime(date('Y-m-d'))?'alert-success':'alert-warning'); ?>
				text-center">
					<div>
					<?php echo $session->name; ?>
					</div>
					<?php if(strtotime($session->start_date)==strtotime(date('Y-m-d'))) { ?>
						<a class="btn btn-danger btn-sm btn-block" href="#">
						شرکت در کلاس
						</a>
					<?php } ?>
					<?php if($session->file_path) { ?>
					<div>
						<a class="btn btn-success btn-sm btn-block" href="<?php echo $session->file_path; ?>">
						دریافت آزمون
						</a>
						<hr/>
					</div>
					<?php } ?>
					<?php if($homeworkUploaded) { ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 10px;">
							تکلیف با موفقیت ارسال شد
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					<?php } ?>
					<div>
						<form method="POST" enctype="multipart/form-data">
							<input type="hidden" name="video_session_id" value="<?php echo $session->id; ?>" />
							<input type="file" name="file_path" class="form-control" />
							<button class="btn btn-warning btn-sm btn-block">
							ارسال تکلیف
							</button>
						</form>
					</div>
					<div class="col-4">
					</div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
	<script>
		function mm_refresh_video_classes() {
			let cats = [];
			jQuery("select.mm-video-class-cat option:selected").each(function(id, field) {
				if(!isNaN(parseInt(jQuery(field).val(), 10))) {
					cats.push(jQuery(field).val());
				}
			});
			jQuery("div.mm-video-class-objects").hide();
			jQuery("div.mm-video-class-objects").each(function(id, field) {
				let blockCats = jQuery(field).data('categories').split(',');
				let showThis = false;
				for(let cat of cats) {
					if(jQuery.inArray(String(cat), blockCats)>=0) {
						showThis = true;
					}
				}
				if(showThis || cats.length==0) {
					jQuery(field).show();
				}
			});
		}
	</script>
	<?php
}

function mm_woocommerce_account_mm_videoclass_mine_endpoint() {
	$vs = new VideoSession();
	$allResults = $vs->loadMineClasses(true);
	$results = $allResults["classes"];
	$mineSessions = $allResults['sessions'];
	?>
	<!-- TEMP -->
	<!-- <script src="https://kit.fontawesome.com/c9fca311fa.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
	<style>
		.smallbox{
			border-radius: 2px;
			position: relative;
			display: block;
			margin-bottom: 20px;
			box-shadow: 0 1px 1px rgba(0,0,0,0.1);
			color: white;
			padding: 5px;
			text-align: right;
			direction: rtl;
		}
		.bg-green{
			background: #00a65a !important;
		}
		.bg-purple{
			background: #605ca8 !important;
		}
		.bg-maroon{
			background: #d81b60 !important;
		}
		.big-icon{
			position: absolute;
			left: 20px;
			top: -3px;
			font-size: 55px;
		}
		.icon-color-green{
			color: rgb(82, 191, 142);
		}
		.icon-color-purple{
			color: rgb(152, 35, 150);
		}
		.icon-color-maroon{
			color: rgb(157, 30, 74);
		}
		.small-font{
			font-size: 13px !important;
		}
		.box{
			margin: 0px 25px 0px 0px;
			background-color: #fff;
		}
		.box-header{
			border-bottom: solid 1px #eaeaea;
			padding: 10px;
		}
		.box-header-blue{
			border-top: solid 3px rgb(32, 106, 223);
		}
		.box-header-orange{
			border-top: solid 3px rgb(246, 149, 88);
		}
		.box-header-green{
			border-top: solid 3px rgb(33, 187, 26);
		}
		.float-left{
			float: left;
		}
		.margin-right-n2{
			margin-right: -2px;
		}
		.day-of-week{
			padding: 10px;
			background-color: #ffe4c4;
			border: solid 1px #fff;
		}
		.red-header {
			background-color: #d81b60;
			color: #fff;
		}
		.color-white{
			color: #fff !important;
		}
		th, td{
			border: none;
		}
	</style>
	<div class="content">
		<div class="row">
			<div class="col-12 text-right">
				<div class="box margin-right-n2">
					<div class="box-header box-header-green p-0">
						<h4 class="mt-4">
							<i class="fa fa-coffee"></i>
							دوره های کامل ثبت نام شده
						</h4>
					</div>
					<?php // var_dump($mineSessions); ?>
					<div class="box-body text-center small-font">
						<table class="table table-striped table-borderless">
							<thead>
								<tr>
									<th scope="col">
									#
									</th>
									<th scope="col">
									نام
									</th>
									<th scope="col">
									زمان برگذاری
									</th>
									<th scope="col">
									نمایش فیلم ضبط شده
									</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach($mineSessions as $class_id => $theSessions) { ?>
								<?php if(count($theSessions["sessions"])==0 && isset($theSessions["recent_sessions"][0])) { ?>
								<tr>
									<td scope="col">
									<?php echo $i; ?>
									</td>
									<td>
										<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?class_id=' . $class_id); ?>">
											<?php echo $theSessions["name"]; ?><!-- <br/> -->
											<?php // echo $theSession["name"]; ?>
										</a>
									</td>
									<td>
									<?php echo '<span>' . jdate("l j F Y", strtotime($theSessions["recent_sessions"][0]->start_date )). '</span> , <span>' . date("H:i", strtotime($theSessions["recent_sessions"][0]->start_time)) . '</span>'; ?>
									</td>
									<td>
										<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?class_id=' . $class_id); ?>" class="btn btn-primary btn-sm">فیلم ضبط شده</a>
									</td>
								</tr>
								<?php $i++; ?>
								<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function mm_woocommerce_account_mm_videoclass_session_endpoint() {
	$vs = new VideoSession();
	$allResults = $vs->loadMineClasses(true);
	$results = $allResults["classes"];
	$mineSessions = $allResults['sessions'];
	?>
	<!-- TEMP -->
	<!-- <script src="https://kit.fontawesome.com/c9fca311fa.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
	<style>
		.smallbox{
			border-radius: 2px;
			position: relative;
			display: block;
			margin-bottom: 20px;
			box-shadow: 0 1px 1px rgba(0,0,0,0.1);
			color: white;
			padding: 5px;
			text-align: right;
			direction: rtl;
		}
		.bg-green{
			background: #00a65a !important;
		}
		.bg-purple{
			background: #605ca8 !important;
		}
		.bg-maroon{
			background: #d81b60 !important;
		}
		.big-icon{
			position: absolute;
			left: 20px;
			top: -3px;
			font-size: 55px;
		}
		.icon-color-green{
			color: rgb(82, 191, 142);
		}
		.icon-color-purple{
			color: rgb(152, 35, 150);
		}
		.icon-color-maroon{
			color: rgb(157, 30, 74);
		}
		.small-font{
			font-size: 13px !important;
		}
		.box{
			margin: 0px 25px 0px 0px;
			background-color: #fff;
		}
		.box-header{
			border-bottom: solid 1px #eaeaea;
			padding: 10px;
		}
		.box-header-blue{
			border-top: solid 3px rgb(32, 106, 223);
		}
		.box-header-orange{
			border-top: solid 3px rgb(246, 149, 88);
		}
		.box-header-green{
			border-top: solid 3px rgb(33, 187, 26);
		}
		.float-left{
			float: left;
		}
		.margin-right-n2{
			margin-right: -2px;
		}
		.day-of-week{
			padding: 10px;
			background-color: #ffe4c4;
			border: solid 1px #fff;
		}
		.red-header {
			background-color: #d81b60;
			color: #fff;
		}
		.color-white{
			color: #fff !important;
		}
		th, td{
			border: none;
		}
	</style>
	<div class="content">
		<div class="row">
			<div class="col-12 text-right">
				<div class="box margin-right-n2">
					<div class="box-header box-header-green p-0">
						<h4 class="mt-4">
							<i class="fa fa-coffee"></i>
							تک جلسات ثبت نام شده
						</h4>
					</div>
					<div class="box-body text-center small-font">
						<table class="table table-striped table-borderless">
							<thead>
								<tr>
									<th scope="col">
									#
									</th>
									<th scope="col">
									نام
									</th>
									<th scope="col">
									زمان برگذاری
									</th>
									<th scope="col">
									نمایش فیلم ضبط شده
									</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach($mineSessions as $theSessions) { ?>
								<?php if(isset($theSessions["sessions"][0]) && !isset($theSessions["recent_sessions"][0])) { ?>
								<?php foreach($theSessions["sessions"] as $theSession) { ?>
								<tr>
									<td scope="col">
									<?php echo $i; ?>
									</td>
									<td>
										<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $theSession["id"]); ?>">
											<?php echo $theSessions["name"]; ?><br/>
											<?php echo $theSession["name"]; ?>
										</a>
									</td>
									<td>
									<?php echo '<span>' . $theSession["start_date"] . '</span> , <span>' . $theSession['start_time'] . '</span>'; ?>
									</td>
									<td>
										<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $theSession["id"]); ?>" class="btn btn-primary btn-sm">فیلم ضبط شده</a>
									</td>
								</tr>
								<?php $i++; ?>
								<?php } ?>
								<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function mm_woocommerce_account_mm_videoclass_sessiondetails_endpoint() {
	if(!isset($_GET['session_id']) && !isset($_GET['class_id'])) {
		echo "دسترسی غیر مجاز";
		return;
	}
	$passed = false;
	if(isset($_GET['session_id'])) {
		$vs = new VideoSession($_GET['session_id']);
		$vs->loadItem();
	} else {
		$vs = new VideoSession;
		$allResults = $vs->loadByItem($_GET['class_id'], true, true);
		$passed = $allResults['passed'];
		$results = $allResults['results'];
		$vs->loadItem();
	}
	$homeworkUploaded = false;
	if(isset($_POST['video_session_id'])) {
		$vh = new VideoHomework();
		$data = [
			'video_session_id'=>$_POST['video_session_id'],
		];
		if(isset($_FILES['file_path']) && $_FILES['file_path']['name'][0]!='') {
			for($index = 0;$index < count($_FILES['file_path']['name']);$index++) {
				$name = $_FILES['file_path']['name'][$index];
				$ext = explode(".", $name);
				$ext = $ext[count($ext)-1];
				$fileName = strtotime(date("Y-m-d H:i:s")) . '_' . $index . '.' . $ext;
				if(!in_array(strtolower($ext), ['php', 'js', 'htm', 'html', 'css'])) {
					if(move_uploaded_file($_FILES["file_path"]["tmp_name"][$index], wp_get_upload_dir()['path'] . $fileName)) {
						$data['file_path'] = wp_get_upload_dir()['url'] . $fileName;
						$vh->insert($data);
						$homeworkUploaded = true;
					}
				}
			}
		}
		
	}
	// var_dump($vs);
	?>
	<!-- TEMP -->
	<!-- <script src="https://kit.fontawesome.com/c9fca311fa.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
	<style>
		.smallbox{
			border-radius: 2px;
			position: relative;
			display: block;
			margin-bottom: 20px;
			box-shadow: 0 1px 1px rgba(0,0,0,0.1);
			color: white;
			padding: 5px;
			text-align: right;
			direction: rtl;
		}
		.bg-green{
			background: #00a65a !important;
		}
		.bg-purple{
			background: #605ca8 !important;
		}
		.bg-maroon{
			background: #d81b60 !important;
		}
		.bg-red{
			background: #d51e2e !important;
		}
		.bg-blue{
			background: #5086e1 !important;
		}
		.bg-stress{
			background: #e95f28!important;
		}
		.big-icon{
			position: absolute;
			left: 20px;
			top: -3px;
			font-size: 55px;
		}
		.icon-color-green{
			color: rgb(82, 191, 142);
		}
		.icon-color-purple{
			color: rgb(152, 35, 150);
		}
		.icon-color-maroon{
			color: rgb(157, 30, 74);
		}
		.small-font{
			font-size: 13px !important;
		}
		.box{
			margin: 0px 25px 0px 0px;
			background-color: #fff;
		}
		.box-body{
			border-top: solid 1px #eaeaea;
			padding-top: 10px;
		}
		.box-header{
			border-bottom: solid 1px #eaeaea;
			padding: 10px;
		}
		.box-header-blue{
			border-top: solid 3px rgb(32, 106, 223);
		}
		.box-header-orange{
			border-top: solid 3px rgb(246, 149, 88);
		}
		.box-header-green{
			border-top: solid 3px rgb(33, 187, 26);
		}
		.float-left{
			float: left;
		}
		.margin-right-n2{
			margin-right: -2px !important;
		}
		.margin-right-n12{
			margin-right: -12px !important;
		}
		.day-of-week{
			padding: 10px;
			background-color: #ffe4c4;
			border: solid 1px #fff;
		}
		.red-header {
			background-color: #d81b60;
			color: #fff;
		}
		.color-white{
			color: #fff !important;
		}
		th, td{
			border: none;
		}
	</style>
	<div class="content">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="mb-50">
					<?php if(!$passed) { ?>
					<?php echo $vs->name; ?>
					|
					<?php } ?>
					<?php echo $vs->CLASS_NAME; ?>
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 mb-30">
				<div class="card">
					<div class="card-header">
						<h4 class="m-0">
							<i class="fa fa-coffee"></i>
							ورود به کلاس
						</h4>
					</div>
					<div class="card-body text-center small-font">
						<?php if($passed) { ?>
						<div class="">
							<h3 class="h4">
							کلاس  به اتمام رسیده
							</h3>
							<h4 class="h5 text-muted">
							جلسات برگزار شده را از روبرو دریافت کنید
							</h4>
						</div>
						<?php } else { ?>
						<?php if((strtotime($vs->SESSION_DATE)==strtotime(date("Y-m-d"))) && (strtotime($vs->SESSION_TIME)>strtotime(date("H:i:s")))) { ?>
						<div class="">
							<h3 class="h4">
							کلاس امروز برگزار می شود
							</h3>
							<h4 class="h5 text-muted">
							زمان برگزاری کلاس :
							</h4>
							<h4 class="h5">
								<?php echo jdate("l j F Y", strtotime($vs->SESSION_DATE)); ?>
								,
								<?php echo date("H:i", strtotime($vs->SESSION_TIME)); ?>
							</h4>
						</div>
						<?php } else if((strtotime($vs->SESSION_DATE)==strtotime(date("Y-m-d"))) && (strtotime($vs->SESSION_TIME)<=strtotime(date("H:i:s"))) && (strtotime($vs->SESSION_END)>strtotime(date("H:i:s")))) { ?>
						<div class="">
							<h3 class="h4">
							کلاس در حال برگزاری است
							</h3>
							<h4 class="h5 text-muted">
							زمان برگزاری کلاس :
							</h4>
							<h4 class="h5 text-muted">
								<?php echo jdate("l j F Y", strtotime($vs->SESSION_DATE)); ?>
								,
								<?php echo date("H:i", strtotime($vs->SESSION_TIME)); ?>
							</h4>
						</div>
						<?php } else if(strtotime($vs->SESSION_DATE)<strtotime(date("Y-m-d"))) { ?>
						<div class="">
							<h3 class="h4">
							کلاس قبلا برگزار شده است
							</h3>
							<h4 class="h5 text-muted">
							زمان برگزاری کلاس :
							</h4>
							<h4 class="h5 text-muted">
								<?php echo jdate("l j F Y", strtotime($vs->SESSION_DATE)); ?>
								,
								<?php echo date("H:i", strtotime($vs->SESSION_TIME)); ?>
							</h4>
						</div>
						<?php } else{ ?>
						<div class="">
							<h3 class="h4">
							کلاس  برگزار خواهد شد
							</h3>
							<h4 class="h5 text-muted">
							زمان برگزاری کلاس :
							</h4>
							<h4 class="h5 text-muted">
								<?php echo jdate("l j F Y", strtotime($vs->SESSION_DATE)); ?>
								,
								<?php echo date("H:i", strtotime($vs->SESSION_TIME)); ?>
							</h4>
						</div>
						<?php }?>
						<?php }?>
					</div>
					<div class="card-footer">
						<a class="btn btn-sm btn-block btn-primary ml-1 mt-3" href="<?php echo site_url('/my-account/mm_videoclass_play/?session_id=' . $vs->id); ?>">
							نمایش فیلم ضبط شده HD
						</a>
						<!-- <a class="btn btn-sm btn-block btn-primary ml-1 mt-3" href="#">
							نمایش فیلم ضبط شده
						</a> -->
						<?php if($vs->file_path) { ?>
						<a class="btn btn-sm btn-block btn-danger ml-1 mt-3" href="<?php echo $vs->file_path; ?>" target="_blank">
							دریافت جزوه
						</a>
						<?php } ?>
						<?php if($homeworkUploaded) { ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 10px;">
							تکلیف با موفقیت ارسال شد
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<?php } ?>
						<?php if($vs->adobe_path) { ?>
						<a class="btn btn-sm btn-block btn-primary ml-1 mt-3" href="http://185.53.140.138/<?php echo $vs->adobe_path; ?>">کلاس زنده</a>
						<?php } ?>
						<form method="POST" enctype="multipart/form-data">
							<br/>
							<input type="hidden" name="video_session_id" value="<?php echo $vs->id; ?>" />
							<input type="file" name="file_path[]" class="form-control" multiple />
							<button class="btn btn-sm btn-block btn-primary ml-1 mt-3">
							ارسال تکلیف
							</button>
						</form>
						<!-- <a class="btn btn-sm btn-block btn-primary ml-1 mt-3" href="#">
							ارسال تکلیف
						</a> -->
					</div>
				</div>
			</div>
			<?php if(!$passed) { ?>
			<div class="col-lg-8 mb-30">
				<div class="card">
					<div class="card-header">
						<h4 class="m-0">
							<i class="fa fa-bookmark"></i>
							<?php echo $vs->name; ?>
						</h4>
					</div>
					<div class="card-body">
						<h3 class="h4">
							سرفصل : <?php echo $vs->name; ?>
						</h3>
					</div>
				</div>
			</div>
			<?php } else { ?>
			<div class="col-lg-8 mb-30">
				<div class="card">
					<div class="card-header">
						<h4 class="m-0">
							<i class="fa fa-bookmark"></i>
							جلسات
							<?php echo $vs->CLASS_NAME; ?>
						</h4>
					</div>
					<div class="card-body">
						<?php foreach($results as $result) { ?>
						<div class="">
							<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $result->id); ?>">
							<?php echo $result->name; ?>
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>

		<div>
			<div class="card">
				<div class="card-header">
					<h4 class="m-0">
						نیازمندی های استفاده از کلاس زنده
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-4">
							<h3>دانلود نرم افزار adobe connect</h3>
							<ul class="addin-links" style="list-style-type: none;padding: 0;">
							<li><a href="https://www.adobeconnect.ir/APK/www.adobeconnect.ir.mobile.Last.Ver.apk" target="_blank"><img src="https://www.adobeconnect.ir/images/google-play-adobe.png" alt="Adobe Connect Mobile for Android devices دانلود ادوب کانکت " title="Android Apps - Download latest Adobe Connect Tablet &amp; Mobile v2.6.9"></a></li>
							<!--     <li><a href="https://play.google.com/store/apps/details?id=air.com.adobe.connectpro" target="_blank"><img src="https://www.adobeconnect.ir/images/google-play-adobe.png" alt="Adobe Connect Mobile for Android devices دانلود ادوب کانکت " title="Android Apps on Google Play - Download latest Adobe Connect Tablet &amp; Mobile" /></a></li>
							<!--  <li><a href="http://itunes.apple.com/ca/app/adobe-connect-mobile/id338279127?mt=8" target="_blank"><img src="https://www.adobeconnect.ir/images/Adobe-app-store.png" alt="Adobe Connect Mobile for iOS devices" title="Adobe Connect Mobile for iOS devices - Download latest Version" /></a></li>               -->
										<li><a href="https://itunes.apple.com/us/app/adobe-connect-mobile/id430437503?mt=8" target="_blank"><img src="https://www.adobeconnect.ir/images/Adobe-app-store.png" alt="Adobe Connect Mobile for iOS devices" title="Adobe Connect Mobile for iOS devices - Download latest Version"></a></li>
										<li><a href="https://www.adobeconnect.ir/go/adobeconnect_9_addin_win"><img src="https://www.adobeconnect.ir/images/badge-windows-min.png" alt="add-in adobe connect رایگان" title="Download latest Adobe Connect 9 Meeting Add-in for Windows v2019.1.2.32"></a></li>
										<li><a href="https://www.adobeconnect.ir/go/adobeconnect_9_addin_mac"><img src="https://www.adobeconnect.ir/images/badge-macos-min.png" alt="Adobe Connect 9 Meeting Add-in for Mac دانلود ادوبی کانکت" title="Download latest Adobe Connect 9 Meeting Add-in for Mac"></a></li>
										<li><a href="https://www.adobeconnect.ir/zip/Adobe.Flash.Player.Last.Ver_www.adobeconnect.ir.zip"><img src="https://www.adobeconnect.ir/images/badge-Flash-Player-fa-min.png" alt="Adobe Flash Player" title="دانلود و بروز رسانی فلش پلیر Adobe.Flash.Player v32.0.0.142 به همراه کیبورد فارسی"></a></li>	

							<!--  <li><a href="http://www.adobeconnect.ir/zip/Farsi.Keyboard(www.adobeconnect.ir).zip"><img src="https://www.adobeconnect.ir/images/Persian.keyboard.png" alt=" Adobe Connect کیبورد فارسی جهت استفاده در گفتگوی " title="تصحیح کیبورد جهت استفاده در چت" /></a></li>      
											-->	
											</ul>
						</div>


						<div class="col-8">

							نیازمندی های استفاده از کلاس زنده<br>
							به منظور دسترسی به کلاس های آنلاین عارف شما هم از طریق کامپیوتر و لپ تاپ شخصی میتوانید به راحتی وارد کامپیوتر شوید و یا اینکه از گوشی هوشمند و یا تبلت آندرویدی خود استفاده کنید.<br>
							بهترین روش برای دسترسی به کلاس از طریف لپتاپ یا کامپیوتر شخصی ویندوزی، استفاده از مرورگر کروم می باشد البته از فایرفاکس هم میتوانید استفاده نمایید<br>
							در صورتیکه از آخرین نسخه کروم استفاده نمایید نیازی به نصب flash player ندارید برای مشاهده کلاس از طریق کامپیوتر ابتدا فایل کروم را از لینک زیر نصب کنید و سپس فلش پلیر مربوطه را دانلود و نصب نمایید.<br>
							همچنین میتوانید نرم افزار adobe connect برای ویندوز را مستقیما نصب کنید.<br>
							دانلود adobe connect ویندوز<br>
							<a href="https://www.adobeconnect.ir/go/adobeconnect_9_addin_win">دانلود</a><br>
							دانلود کروم برای ویندوز 32 بیتی<br>
							<a href="https://www.google.com/chrome/"> https://enterprise.google.com/chrome/chrome-browser/thankyou.html?platform=win32msi</a><br>
							دانلود کروم برای ویندوز 64 بیتی<br>
							<a href="https://www.google.com/chrome/"> https://enterprise.google.com/chrome/chrome-browser/thankyou.html?platform=win64msi</a><br>
							دانلود فلش پلیر برای کروم<br>
							<a href="http://my.classino.com/uploads/documents/chrome.zip"> chrome.zip</a><br>
							در صورتیکه از گوشی هوشمند آندرویدی و یا آیفون استفاده میکنید برای دسترسی به کلاس حتما می باید نرم افزار adobe connect  را نصب کنید.<br>
							دانلود adobe connect برای آیفون<br>
							گوشی های آیفون به زودی پشتیبانی خواهد شد<br>
							دانلود adobe connect  برای آندروید<br>
							<a href="https://cafebazaar.ir/app/air.com.adobe.connectpro/?l=fa">https://cafebazaar.ir/app/air.com.adobe.connectpro/?l=fa</a><br>

							سپس در ساعت شروع کلاس به لینک کلاس خود مراجعه نمایید و روی ورود به کلاس کلیک نمایید.<br>
							در گوشی حتما لینک را با نرم افزار adobe connect  باز کنید.<br>
							دانلود نرم افزار anydesk برای دسترسی دادن به پشتیبان عارف جهت بررسی مشکلات کامپیوتر شما<br>
							<a href="http://my.classino.com/uploads/documents/anydesk.zip"> anydesk.zip</a>

							<h4>پشتیبانی فنی</h4>
							<p class="small-font">درصورت بروز هرگونه مشکل فنی با شماره زیر تماس بگیرید.</p>
							<p class="small-font"><b>09153068145</b></p>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function mm_woocommerce_account_mm_videoclass_play_endpoint() {
	if(!isset($_GET['session_id'])) {
		echo "دسترسی غیر مجاز";
		return;
	}
	$vs = new VideoSession($_GET['session_id']);
	?>
	<!-- <video width="352" height="198" controls>
    <source src="<?php echo $vs->video_link; ?>" type="application/x-mpegURL">
	</video> -->
	<style>
	 .r1_iframe_embed { position: relative; overflow: hidden; width: 100%; height: auto; padding-top: 56.25%; } 
	 .r1_iframe_embed iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; } 
	</style> 
	<div class="r1_iframe_embed"> 
	<iframe src="<?php echo $vs->video_link; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe> 
	</div>
	<?php
}

function dateDiff($theDate) {
	$now = strtotime(date("Y-m-d H:i:s"));
	$then = $theDate;
	$diff = $then - $now;
	$days = ceil(($diff) / (60*60*24));
	return $days;
}

function mm_dashboard() {
	$vs = new VideoSession;
	$allResults = $vs->loadMineClasses(true);
	$results = $allResults["classes"];
	$mineSessions = $allResults['sessions'];

	$current_user = wp_get_current_user();
	$konkoorDate = explode("/", "1399/04/13");
	$konkoorDate = jalali_to_gregorian($konkoorDate[0], $konkoorDate[1], $konkoorDate[2]);
	$konkoorTimeStamp = strtotime($konkoorDate[0].'-'.$konkoorDate[1].'-'.$konkoorDate[2].' 19:30:00') * 1000;
	$daysRemain = dateDiff($konkoorTimeStamp/1000);
	$daysPercent = ceil($daysRemain*100/365);
	$now = date("l");
	if($now!='Saturday') {
		$startOfTheWeek = date("Y-m-d", strtotime("last Saturday"));
	}else {
		$startOfTheWeek = date("Y-m-d");
	}
	$todaySessions = [];
	for($i = 0;$i < 7;$i++) {
		foreach($mineSessions as $theSessions) {
			if(isset($theSessions["sessions"][0])) {
				foreach($theSessions["sessions"] as $theSession) {
					if(strtotime($theSession["session_date"])==strtotime($startOfTheWeek . ' + ' . $i . ' days')) {
						$todaySessions[] = [
							"class"=>$theSessions,
							"session"=>$theSession,
						];
					}
				}
			}else {
				foreach($theSessions["recent_sessions"] as $theSession) {
					if(strtotime($theSession->start_date)==strtotime($startOfTheWeek . ' + ' . $i . ' days')) {
						$todaySessions[] = [
							"class"=>$theSessions,
							"session"=>$theSession,
						];
					}
				}
			}
		}
	}
	?>
	<!-- TEMP -->
	<!-- <script src="https://kit.fontawesome.com/c9fca311fa.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
	<!--\TEMP -->
	<style>
		.smallbox{
			border-radius: 2px;
			position: relative;
			display: block;
			margin-bottom: 20px;
			box-shadow: 0 1px 1px rgba(0,0,0,0.1);
			color: white;
			padding: 5px;
			text-align: right;
			direction: rtl;
		}
		.bg-green{
			background: #00a65a !important;
		}
		.bg-purple{
			background: #605ca8 !important;
		}
		.bg-maroon{
			background: #d81b60 !important;
		}
		.big-icon{
			position: absolute;
			left: 20px;
			top: -3px;
			font-size: 55px;
		}
		.icon-color-green{
			color: rgb(82, 191, 142);
		}
		.icon-color-purple{
			color: rgb(152, 35, 150);
		}
		.icon-color-maroon{
			color: rgb(157, 30, 74);
		}
		.small-font{
			font-size: 13px !important;
		}
		.box{
			margin: 0px 25px 0px 0px;
			background-color: #fff;
		}
		.box-header{
			border-bottom: solid 1px #eaeaea;
			padding: 10px;
		}
		.box-header-blue{
			border-top: solid 3px rgb(32, 106, 223);
		}
		.box-header-orange{
			border-top: solid 3px rgb(246, 149, 88);
		}
		.float-left{
			float: left;
		}
		.margin-right-n2{
			margin-right: -2px;
		}
		.day-of-week{
			padding: 10px;
			background-color: #ffe4c4;
			border: solid 1px #fff;
		}
		.red-header {
			background-color: #d81b60;
			color: #fff;
		}
		.color-white{
			color: #fff !important;
		}
	</style>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4 mb-30">

				<div class="card mb-30">
					<div class="card-header">
						<h4 class="m-0">
							<i class="fa fa-hourglass-half"></i>
							زمان سنج
						</h4>
					</div>
					<div class="card-body">
						<h4 class="h5">زمان باقی مانده تا کنکور ۹۹</h4>
						<div class="my-15 rounded p-10 bg-light">
							<div class="progress" style="height: initial !important;">
								<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $daysPercent; ?>%;height: 2px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
						<div class="mb-half">
							<strong><span class="_mm_konkoor_time_percent"><?php echo $daysPercent; ?></span>%</strong>
							از زمان شما باقی مانده است.
						</div>
						<div>
							<span class="_mm_konkoor_time_left"></span>
						</div>
					</div>
				</div>

				<div class="card mb-30">
					<div class="card-header">
						<h4 class="m-0">
							کلاس های امروز من
							<span class="badge badge-primary">
								<?php echo count($todaySessions); ?>
								کلاس
							</span>
						</h4>
					</div>
					<div class="card-body text-center">
						<canvas id="canvas" width="200" height="200"></canvas>
						<div class="mt-15">
						<?php if(count($todaySessions)==0) { ?>
						امروز کلاسی ندارید
						<?php } else { ?>
							<?php foreach($todaySessions as $todaySession) { ?>
								<div>
									<?php if(is_array($todaySession["session"])) { ?>
									<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $todaySession["session"]["id"]); ?>" class="btn btn-primary btn-sm"><?php echo $todaySession["class"]["name"] . '|' . $todaySession["session"]["name"]; ?></a>
									<?php } else { ?>
									<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $todaySession["session"]->id); ?>" class="btn btn-primary btn-sm"><?php echo $todaySession["class"]["name"] . '|' . $todaySession["session"]->name; ?></a>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } ?>
						</div>
					</div>
				</div>

				<div class="card mb-30">
					<div class="card-header">
						<h4 class="m-0">
							تک جلسات ثبت نام شده	
						</h4>
					</div>
					<div class="card-body">
						<a class="" href="<?php echo site_url('/my-account/mm_videoclass_mine/'); ?>">
							(برای مشاهده فیلمهای ضبط شده تک جلسات کلیک کنید)
						</a>
					</div>
				</div>

			</div>
			<div class="col-lg-8 mb-30">
										
				<div class="row">
					<div class="col-lg-6">
						<div class="card mb-30">
							<div class="card-header">
								<h4 class="m-0">
									<i class="fa fa-user"></i>
									<?php echo esc_html( $current_user->display_name ); ?>
								</h4>
							</div>
							<div class="card-body">
								<div>
									خوش آمدید
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card mb-30">
							<div class="card-header">
								<h4 class="m-0">
									<i class="fa fa-calendar"></i>
									امروز
								</h4>
							</div>
							<div class="card-body">
								<div>
									<?php echo jdate("l d F Y"); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card mb-30">
					<div class="card-header">
						<h4 class="m-0">
							<i class="fa fa-coffee"></i>
							برنامه کلاسی این هفته من
						</h4>
					</div>
					<div class="card-body">
						<div class="py-10 px-20 bg-blue rounded-top">
							<h5 class="m-0 text-white">روز</h5>
						</div>
						<?php for($i = 0;$i < 7;$i++) { ?>
						<div class="py-10 px-20 bg-light border-gray border-bottom <?php echo ($i == 6 ? 'rounded-bottom border-0' : '') ?>">
							<?php echo jdate("l Y/m/d", strtotime($startOfTheWeek . ' + ' . $i . ' days')); ?>
							<?php foreach($mineSessions as $theSessions) { ?>
								<?php if(isset($theSessions["sessions"][0])) { ?>
								<?php foreach($theSessions["sessions"] as $theSession) { ?>
									<?php if(strtotime($theSession["session_date"])==strtotime($startOfTheWeek . ' + ' . $i . ' days')) { ?>
										<br/>
										<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $theSession["id"]); ?>" class="btn btn-primary btn-sm"><?php echo $theSessions["name"] . '|' . $theSession["name"]; ?></a>
									<?php } ?>
								<?php } ?>
								<?php } else { ?>
								<?php foreach($theSessions["recent_sessions"] as $theSession) { ?>
									<?php if(strtotime($theSession->start_date)==strtotime($startOfTheWeek . ' + ' . $i . ' days')) { ?>
										<br/>
										<a href="<?php echo site_url('/my-account/mm_videoclass_sessiondetails/?session_id=' . $theSession->id); ?>" class="btn btn-primary btn-sm"><?php echo $theSessions["name"] . '|' . $theSession->name; ?></a>
									<?php } ?>
								<?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<script>
		let konkoorTimeStamp = <?php echo $konkoorTimeStamp; ?>;
		function dateDiff(theDate) {
			let now = new Date;
			let then = new Date(theDate);
			let diff = then - now;
			let days = Math.floor((diff) / (1000*60*60*24));
			diff -= days*(1000*60*60*24);
			let hours = Math.floor((diff) / (1000*60*60));
			diff -= hours*(1000*60*60);
			let minutes = Math.floor((diff) / (1000*60));
			diff -= minutes*(1000*60);
			let seconds = Math.floor((diff) / (1000));
			return {
				days,
				hours,
				minutes,
				seconds,
			};
		}
		function showRemainigTime() {
			let result = dateDiff(konkoorTimeStamp);
			jQuery("span._mm_konkoor_time_left").html(`<strong>${result.days}</strong> روز : <strong>${result.hours}</strong> ساعت : <strong>${result.minutes}</strong> دقیقه : <strong>${result.seconds}</strong> ثانیه`);
			console.log(result);
			setTimeout(() => {
				showRemainigTime();
				drawClock();
			}, 1000);
		}
		showRemainigTime();
		//------------CLOCK
		var canvas = document.getElementById("canvas");
		var ctx = canvas.getContext("2d");
		var radius = canvas.height / 2;
		ctx.translate(radius, radius);
		radius = radius * 0.90
		// setInterval(drawClock, 1000);

		function drawClock() {
			drawFace(ctx, radius);
			drawNumbers(ctx, radius);
			drawTime(ctx, radius);
		}

		function drawFace(ctx, radius) {
			var grad;
			ctx.beginPath();
			ctx.arc(0, 0, radius, 0, 2*Math.PI);
			ctx.fillStyle = 'white';
			ctx.fill();
			grad = ctx.createRadialGradient(0,0,radius*0.95, 0,0,radius*1.05);
			grad.addColorStop(0, '#333');
			grad.addColorStop(0.5, 'white');
			grad.addColorStop(1, '#333');
			ctx.strokeStyle = grad;
			ctx.lineWidth = radius*0.1;
			ctx.stroke();
			ctx.beginPath();
			ctx.arc(0, 0, radius*0.1, 0, 2*Math.PI);
			ctx.fillStyle = '#333';
			ctx.fill();
		}

		function drawNumbers(ctx, radius) {
			var ang;
			var num;
			ctx.font = radius*0.15 + "px arial";
			ctx.textBaseline="middle";
			ctx.textAlign="center";
			for(num = 1; num < 13; num++){
				ang = num * Math.PI / 6;
				ctx.rotate(ang);
				ctx.translate(0, -radius*0.85);
				ctx.rotate(-ang);
				ctx.fillText(num.toString(), 0, 0);
				ctx.rotate(ang);
				ctx.translate(0, radius*0.85);
				ctx.rotate(-ang);
			}
		}

		function drawTime(ctx, radius){
				var now = new Date();
				var hour = now.getHours();
				var minute = now.getMinutes();
				var second = now.getSeconds();
				//hour
				hour=hour%12;
				hour=(hour*Math.PI/6)+
				(minute*Math.PI/(6*60))+
				(second*Math.PI/(360*60));
				drawHand(ctx, hour, radius*0.5, radius*0.07);
				//minute
				minute=(minute*Math.PI/30)+(second*Math.PI/(30*60));
				drawHand(ctx, minute, radius*0.8, radius*0.07);
				// second
				second=(second*Math.PI/30);
				drawHand(ctx, second, radius*0.9, radius*0.02);
		}

		function drawHand(ctx, pos, length, width) {
				ctx.beginPath();
				ctx.lineWidth = width;
				ctx.lineCap = "round";
				ctx.moveTo(0,0);
				ctx.rotate(pos);
				ctx.lineTo(0, -length);
				ctx.stroke();
				ctx.rotate(-pos);
		}
	</script>
	<?php
}
//\--------ADD MENU---------------------------------