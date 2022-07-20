<?php 
function show_delivery() {
    global $wpdb;
    // сначала сделаем запрос
    $wpdb->query( $wpdb->prepare("SELECT id, region_id, region_name, delivery, cost FROM as_regions") );

    // теперь получим данные этого запроса
    $id          = $wpdb->get_col( null , 0 );
    $region_id   = $wpdb->get_col( null , 1 );  // используется в классе CSS
    $region_name = $wpdb->get_col( null , 2 );
    $delivery    = $wpdb->get_col( null , 3 ); 
    $cost        = $wpdb->get_col( null , 4 );


    foreach ( $id as $v ) {
        $v-- ;
    ?>
    <div class="region-<?php echo $region_id[$v]; ?>">
        <div class="region">
            <div> 
                <?php echo $region_name[$v]; ?><br>
                <span><?php echo $delivery[$v]; ?></span> <?php echo $cost[$v]; ?> грн/бутиль
            </div>
        </div>
    </div>
    <?php } 
}


