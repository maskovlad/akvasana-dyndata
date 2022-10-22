<?php
function meta_description() {
    global $post;
        $post_description = get_post_meta( $post->ID, 'description', true );
        if (!$post_description) {
                $post_description = '💧Доставимо найчистішу й найсмачнішу у місті воду до вашої оселі💧Сучасна американська мембранна технологія очистки💧Лабораторна якість💧Замовляйте на сайті або через Telegram-бота.📲'; 
        }
        echo '<meta name="description" content="' . $post_description . '" />' . "\n";
}
add_action( 'wp_head', 'meta_description');
