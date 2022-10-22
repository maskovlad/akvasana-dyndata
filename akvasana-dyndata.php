<?php
/*
Plugin Name: Akvasana Dynamic Data
Version: 1.3
*/
// включение отображения ошибок php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

require "as-process-data.php";
require "ajax-action-callback.php";
require "menu_page.php";
require "get-data.php";
require "post-data.php";
require "order-form.php";
require "delivery.php";
require "seo.php";

// подключение стилей и скриптов
add_action( 'wp_enqueue_scripts', 'as_scripts' );
function as_scripts() {
    wp_enqueue_script('jquery');
    // Обрабoтка полей формы
	wp_enqueue_script( 'jquery-form' );

    // библиотека для маски ввода телефона
    wp_enqueue_script('inputmask', 'https://rawgit.com/RobinHerbots/Inputmask/5.x/dist/jquery.inputmask.js', array('jquery'));

    // стили и скрипты плагина
	wp_enqueue_style( 'as-style', plugins_url( 'css/as-style.css', __FILE__ ) );
	wp_enqueue_style( 'as-elements', plugins_url( 'css/as-elements.css', __FILE__ ) );
	wp_enqueue_style( 'as-feedback', plugins_url( 'css/feedback.css', __FILE__ ) );
    wp_enqueue_script(
        'as-main',
        plugins_url( 'js/main.js', __FILE__ ),
        array('jquery', 'inputmask'),
        '1.0', 
        'in_footer');

        // ajax обработчик формы
    wp_enqueue_script(
		'feedback',
		plugins_url( 'js/feedback.js', __FILE__ ),
		array( 'jquery' ),
		'1.0',
		'in_footer'
	);

        // Задаем данные обьекта ajax для скрипта feedback.js
    wp_localize_script(
        'feedback',
        'feedback_object',
        array(
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'feedback-nonce' ),
        )
    );
        
            /* библиотека для анимации радио и чекбоксов */
	wp_enqueue_script('gsap', "https://unpkg.co/gsap@3/dist/gsap.min.js");

}

//------------Страница меню настроек в админке-------

// Hook for adding admin menus
add_action('admin_menu', 'as_add_page');
// action function for above hook
function as_add_page() {
    // Add a new top-level menu (ill-advised):
    add_menu_page('Настройки АкваСана', 'Настройки АкваСана', 'manage_options', 'as_menu_page','as_menu_page_callback', '', 4);
    add_submenu_page('as_menu_page','Настройки АкваСана', 'Цена воды', 'manage_options', 'price_page', 'price_page_callback');
    add_submenu_page('as_menu_page','Настройки АкваСана', 'Цена аксессуаров', 'manage_options', 'accessory_page', 'accessory_page_callback');
}
// скрытие для пользователя serg пунктов меню
add_action('admin_init', 'remove_admin_menu_links');
function remove_admin_menu_links(){
    $user = wp_get_current_user();
    if( $user && isset($user->user_login) && 'serg'  == $user->user_login ) {
        remove_menu_page('tools.php');      // инструменты
        remove_menu_page('index.php');      // инструменты
        remove_menu_page('anwp-post-grid');             // плагин
        remove_menu_page('elementor');                  // плагин
        remove_menu_page('my-stickymenu-welcomebar');   // плагин
        remove_menu_page('duplicator');                 // плагин
        remove_menu_page('edit.php?post_type=elementor_library');   // плагин
        remove_menu_page('themes.php');     // темы
        remove_menu_page('options-general.php');    // настройки
        remove_menu_page('plugins.php');            // плагины
        remove_menu_page('users.php');              // пользователи
        remove_menu_page('edit-comments.php');      // комментарии
        // remove_menu_page('page.php');
        remove_menu_page('upload.php');             // медиафайлы
        remove_menu_page( 'edit.php?post_type=page' ); // страницы
        // remove_menu_page( 'edit.php?post_type=videos' );
        // remove_menu_page( 'edit.php' );           // записи

    }
}

// ------------ФОРМА ЗАКАЗА---------------
add_shortcode('as_order_form', 'as_show_order_form');
    // обработка формы
add_action( 'wp_ajax_feedback_action', 'ajax_action_callback' );
add_action( 'wp_ajax_nopriv_feedback_action', 'ajax_action_callback' );


/*==== вывод данных в графике доставки =====*/
add_shortcode( 'as_delivery', 'show_delivery' );

/*============= REST API ===========*/
// GET
add_action( 'rest_api_init', function() {
    register_rest_route( 'as/v1', 'get_data', [
        'methods' => 'GET',
        'callback' => '_get_data',
    ]);
});

// POST
add_action( 'rest_api_init', function() {
    register_rest_route( 'as/v1', 'post_data', [
        'methods' => 'POST',
        'callback' => '_post_data',
        'args' => [
            'apps' => [
                'type'=> 'string',
                'required' => true,
	        ],
            'region' => [
                'type'=> 'string',
                'required' => true,
	        ],
            'address' => [
                'type'=> 'string',
                'required' => true,
	        ],
            'phone' => [
                'type'=> 'string',
                'required' => true,
	        ],
            'is_client' => [
                'type'=> 'string',          //?
                'required' => true,
	        ],
            'bottle_need' => [
                'type'=> 'string',          //?
                'required' => true,
	        ],
            'pomp_need' => [
                'type'=> 'string',          //?
                'required' => true,
	        ],
            'quantity_bottles' => [
                'type'=> 'integer',          //?
                'required' => true,
	        ],
            'total' => [
                'type'=> 'integer',          //?
                'required' => true,
	        ],
        ]
    ]);
});

