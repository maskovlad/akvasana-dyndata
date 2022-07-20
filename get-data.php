<?php 

function _get_data() {

    global $wpdb;

    $data_regions = $wpdb->get_results($wpdb->prepare(
        "SELECT id, region_name AS regionName, min_quantity as minQty, cost, cost1 FROM as_regions"));

    // todo: сделать массив на случай, если добавятся еще аксессуары
    // todo: тогда в ответ рест апи добавить массив accessory[], куда включить pomp & bottle
    $data_bottle_cost = $wpdb->get_row($wpdb->prepare(
        "SELECT item, cost FROM as_accessory where item = 'bottle'"));
    $data_pomp_cost = $wpdb->get_row($wpdb->prepare(
        "SELECT item, cost FROM as_accessory where item = 'pomp'"));
    $delivery = $wpdb->get_results($wpdb->prepare(
        "SELECT id, region_name AS regionName, delivery FROM as_regions"));
    $contacts = $wpdb->get_results($wpdb->prepare(
        "SELECT id,name,value,display,title FROM as_app_contacts"));

    $new_arr = array('regSelect' => $data_regions,
                    'bottle' => $data_bottle_cost,
                    'pomp' => $data_pomp_cost,
                    'contacts' => $contacts,
                    'delivery' => $delivery
                );
    echo json_encode($new_arr);
}
?>