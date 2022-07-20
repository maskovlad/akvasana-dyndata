<?php 

function ajax_action_callback() {

    global $wpdb;

	// Массив ошибок
	$err_message = array();

	// Проверяем nonce. Если проверка не прошла, то блокируем отправку
	if ( ! wp_verify_nonce( $_POST['nonce'], 'feedback-nonce' ) ) {
		wp_die( 'Данные отправлены с левого адреса' );
	}
	// Проверяем на спам. Если скрытое поле заполнено или снят чек, то блокируем отправку
	if ( false === $_POST['as_chk_padlo'] || ! empty( $_POST['as_txt_padlo'] ) ) {
		wp_die( 'Пошел нахрен, робот C3PO !(c)' );
	}

    // Проверяем поле района, если пустое, то пишем сообщение в массив ошибок
	if ( empty( $_POST['regions'] ) || ! isset( $_POST['regions'] ) ) {
		$err_message['regions'] = 'Будь ласка, виберіть свій район.';
	} else {
		$regions = sanitize_text_field( $_POST['regions'] );
	}

	// Проверяем поле адреса, если пустое, то пишем сообщение в массив ошибок
	if ( empty( $_POST['as-address'] ) || ! isset( $_POST['as-address'] ) ) {
		$err_message['address'] = 'Будь ласка, введіть адресу.';
	} else {
		$address = sanitize_text_field( $_POST['as-address'] );
	}

	// Проверяем поле телефона, если пустое, то пишем сообщение в массив ошибок
	if ( empty( $_POST['as-phone'] ) || ! isset( $_POST['as-phone'] ) ) {
		$err_message['phone'] = 'Будь ласка, введіть свій телефон.';
	} else {
		$phone = sanitize_text_field( $_POST['as-phone'] );
	}

	// Проверяем переключатель "Чи є ви клієнтом Аква Сана"
	if ( empty( $_POST['as-is-client'] ) || ! isset( $_POST['as-is-client'] ) ) {
		$err_message['is-client-yes'] = 'Будь ласка, виберіть одне з двох.';
	} else {
		$is_client = $_POST['as-is-client'];
	}

	// Проверяем нажат ли чекбокс "Чи потрібна тара"
	if ( isset( $_POST['bottle-need'] ) && !empty($_POST['bottle-need']) ) {
		$bottle_need = '+';
	} else {
		$bottle_need = '-';
	}

	// Проверяем нажат ли чекбокс "Чи потрібна тара"
	if ( isset( $_POST['pomp-need'] ) && !empty($_POST['pomp-need']) ) {
		$pomp_need = '+';
	} else {
		$pomp_need = '-';
	}

    // количество заказанных бутлей
	if ( isset( $_POST['quantity-bottles'] ) && !empty($_POST['quantity-bottles']) ) {
		$quantity_bottles = $_POST['quantity-bottles'];
	} else {
		$quantity_bottles = 'Помилка! Зверніться до адміністратора!';
	}

    // сумма
	if ( isset( $_POST['total'] ) && !empty($_POST['total']) ) {
		$total = $_POST['total'];
	} else {
		$total = 'Помилка! Зверніться до адміністратора!';
	}

	// Проверяем массив ошибок, если не пустой, то передаем сообщение. Иначе отправляем письмо
	if ( $err_message ) {
		wp_send_json_error( $err_message );
	} else {
        as_process_data('SITE',$regions, $address, $phone, $is_client, $bottle_need,
                        $pomp_need, $quantity_bottles, $total);
    }
	// На всякий случай убиваем еще раз процесс ajax
	wp_die();

}