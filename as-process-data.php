<?php 
function as_process_data($apps, $regions, $address, $phone, $is_client, $bottle_need,
                        $pomp_need, $quantity_bottles, $total) {
    // записываем данные в БД и получаем номер записи $_insert_id
    $_insert_id = insert_database_entry($apps, $regions, $address, $phone, $is_client, $bottle_need,
                                        $pomp_need, $quantity_bottles, $total);
    // echo "<pre>";
    // echo var_dump($_insert_id);
    // echo "</pre>";
    $insert_id_mail_text = ($_insert_id == 0)
                ?
                    ("ПОМИЛКА НА САЙТІ: НЕ ДОДАНО ЗАПИС ДО БАЗИ ДАНИХ!")
                :
                    ($_insert_id);


    if ( as_send_email($apps, $regions, $address, $phone, $is_client, $bottle_need,
                        $pomp_need, $quantity_bottles, $total, $insert_id_mail_text ) ){
    
        // Отправляем сообщение об успешной отправке
        $message_success = ['Дякуємо за замовлення! Незабаром ми зв\'яжемося з Вами'];
        wp_send_json_success( $message_success );
    } else {
        wp_send_json_error();
        wp_die( "ПОМИЛКА: Не вдалося відправити email" );
    }
    return 0;
}


// функция добавления записи о заказе в базу данных
function insert_database_entry($apps, $region_name, $address, $phone, $is_client, $bottle_need,
                        $pomp_need, $quantity_bottles, $total) {
    global $wpdb;
    
    $table_name = "as_orders";  // имя таблицы

    // проверяем существование таблицы, если ошибка то отправляем сообщение в емейле
    // а если таблица найдена, добавляем новую запись о заказе
    if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '$table_name'")) != $table_name) {
        return 0;
    } else {   
        // подготавливаем данные
        $apps =             esc_sql($apps);
        $region_name =      esc_sql($region_name);
        $address =          esc_sql($address);
        $phone =            esc_sql($phone);
        $is_client =        esc_sql($is_client);
        $bottle_need =      esc_sql($bottle_need);
        $pomp_need =        esc_sql($pomp_need);
        $quantity_bottles = esc_sql($quantity_bottles);
        $total =            esc_sql($total);
    
        // вставляем строку в таблицу
        if (!$wpdb->insert( $table_name, array(
            'apps' => $apps,                      // откуда пришел заказ (сайт, приложение)
            'date_time' => wp_date("Y-m-d H:i:s"),
            'region_name' => $region_name,
            'address' => $address,
            'phone' => $phone,
            'is_client' => $is_client,
            'bottle_need' => $bottle_need,
            'pomp_need' => $pomp_need,
            'quantity_bottles' => $quantity_bottles,
            'total' => $total
        ))) {
            return 0;
        }
    }
    return $wpdb->insert_id;
}

//  функция отправки емейл о поступившем заказе
function as_send_email($apps, $regions, $address, $phone, $is_client, $bottle_need,
                        $pomp_need, $quantity_bottles, $total, $insert_id_mail_text ) {
    // тема письма
    $subject = 'Замовлення № ' . $insert_id_mail_text . ' з '. $apps .' '. wp_date("d-m-y H:i:s");
		
    // тестовий мейл, якщо в полі адреса ввести "test"
    if (strcmp($address, 'test') == 0) {
        $email_to = get_option( 'test_email', 'volodamir69@gmail.com' );
    } else {
        $email_to = get_option( 'order_email', 'akvasana@ukr.net' ); 
    }

    $body    = "<span style=\"font-size: 16pt;\">
				Район:    <b>" . $regions . "</b><br>
                Адреса:   <b>" . $address . "</b><br>
                Телефон:  <b>" . $phone . "</b><br>
                Клієнт?   <b>" . $is_client . "</b><br>
                Тара?     <b>" . $bottle_need . "</b><br>
                Помпа?    <b>" . $pomp_need . "</b><br>
                Кількість <b>" . $quantity_bottles . "</b> шт<br><br>
                Сума      <b>" . $total . "</b> грн<br><br><br>
                </span>
                <span style=\"font-size: 12pt;\">
                From " . $apps . "<br>
                Database entry No: " . $insert_id_mail_text .
				"</span>";

	$headers = 'From: ' . $address . ' <' . $email_to . '>' . "\r\n" . 'Reply-To: ' . $email_to;
	
    // чтобы добавлять теги в письмо
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );
    function set_html_content_type() {
        return 'text/html';
    }

    // Отправляем письмо
    return wp_mail( $email_to, $subject, $body, $headers );
	
}

// функція дістає з таблиці as_option потрібний параметр
function as_option($option_name, $default_value) {
    return 0;
}

