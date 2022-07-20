<?php
require_once "as-process-data.php";

function _post_data(WP_REST_Request $request) {
	$apps = $request['apps'];
	$region = $request['region'];
	$address = $request['address'];
	$phone = $request['phone'];
	$is_client = $request['is_client'];
	$bottle_need = $request['bottle_need'];
	$pomp_need = $request['pomp_need'];
	$quantity_bottles = $request['quantity_bottles'];
	$total = $request['total'];
	// $parameters = $request->get_params();
// 	error_log('$parameters'. json_encode($parameters));  // запись заявки в лог

	// запись в БД
	$process = as_process_data($apps, $region, $address, $phone, $is_client, $bottle_need,
                        $pomp_need, $quantity_bottles, $total);
    
    // отправка заявки на почту
	// as_send_email('ANDROID',$region, $address, $phone, $is_client, $bottle_need,
  //                       $pomp_need, $quantity_bottles, $total, 0);

	// echo json_encode($parameters);
}
