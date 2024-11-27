<?php

add_action('doccure_after_order_meta_update', 'doccure_create_customtable_zoom', 10, 3);

function doccure_create_customtable_zoom($order_id, $item_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zoom_links';

    $order = wc_get_order($order_id);
    $product_id = ''; 
    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
    }       

    $doctor_id = wc_get_order_item_meta($item_id, 'doctor_id', true);
    $patient_id = wc_get_order_item_meta($item_id, 'patient_id', true);

    $serialized_data = wc_get_order_item_meta($item_id, 'cus_woo_product_data', true);
    $cus_woo_product_data = maybe_unserialize($serialized_data);

    $slots = isset($cus_woo_product_data['slots']) ? $cus_woo_product_data['slots'] : '';
    $appointment_date = isset($cus_woo_product_data['appointment_date']) ? $cus_woo_product_data['appointment_date'] : '';

    // $zoom_meeting_link = 'https://zoom.us/meeting/us/users/';
    $zoom_meeting_link = generate_zoom_meeting($doctor_id, $patient_id, $appointment_date, $slots);
    $current_time = strtotime(current_time('mysql'));

    $data_to_log = array(
        'order_id' => $order_id,
        'product_id' => $product_id,
        'slots' => $slots,
        'appointment_date' => $appointment_date,
        'meeting_id' => $zoom_meeting_link,
        'timestamp' => $current_time,
        'doctor_id' => $doctor_id,
        'patient_id' => $patient_id
    );

    $existing_entry = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE order_id = %d", $order_id));

    if ($existing_entry == 0) {
        $wpdb->insert($table_name, $data_to_log);
    } else {
        echo "<script>console.log('Order ID $order_id already exists in the Zoom links table. Skipping insert.');</script>";
    }
}



add_action('doccure_after_order_meta_update_offline', 'doccure_create_customtable_zoom_offline', 10, 2);

function doccure_create_customtable_zoom_offline($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zoom_links';

    $doctor_id = get_post_meta($post_id, '_doctor_id', true);
    $patient_id = get_post_meta($post_id, '_patient_id', true);
    $booking_data = get_post_meta($post_id, '_am_booking', true);
    $product_rand_offline = get_post_meta($post_id, '_product_rand_offline', true);

    
    if (!empty($booking_data)) {
        $booking_data = maybe_unserialize($booking_data);
    }

    if (is_array($booking_data)) {
        $appointment_date = isset($booking_data['_appointment_date']) ? $booking_data['_appointment_date'] : '';
        $slots = isset($booking_data['_slots']) ? $booking_data['_slots'] : '';
        
    }

    // $zoom_meeting_link = 'https://zoom.us/meeting/us/users/';
    $zoom_meeting_link = generate_zoom_meeting($doctor_id, $patient_id, $appointment_date, $slots);
    $current_time = strtotime(current_time('mysql'));

    $data_to_log = array(
        'order_id' => $product_rand_offline,
        'product_id' => $product_rand_offline,
        'slots' => $slots,
        'appointment_date' => $appointment_date,
        'meeting_id' => $zoom_meeting_link,
        'timestamp' => $current_time,
        'doctor_id' => $doctor_id,
        'patient_id' => $patient_id
    );

    $existing_entry = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE order_id = %d", $order_id));

    if ($existing_entry == 0) {
        $wpdb->insert($table_name, $data_to_log);
    } else {
        echo "<script>console.log('Order ID $order_id already exists in the Zoom links table. Skipping insert.');</script>";
    }
}
?>