<?php
function generate_zoom_meeting($doctor_id, $patient_id, $appointment_date, $slots) {

    $doccure_options = get_option('doccure_options');
    $client_id = $doccure_options['zoom_oauth_client_id'] ?? '';
    $client_secret = $doccure_options['zoom_oauth_client_secret'] ?? '';
    $account_id = $doccure_options['zoom_oauth_account_id'] ?? '';

    $start_time = '';
    if ($slots) {
        $slot_times = explode('-', $slots);
        $start_time = $slot_times[0]; 
    }

    $formatted_start_time = $appointment_date . 'T' . substr($start_time, 0, 2) . ':' . substr($start_time, 2, 2) . ':00Z';

    $token_url = "https://zoom.us/oauth/token";
    $headers = array(
        'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret),
        'Content-Type' => 'application/x-www-form-urlencoded'
    );

    $body = array(
        'grant_type' => 'account_credentials',
        'account_id' => $account_id
    );

    $response = wp_remote_post($token_url, array(
        'headers' => $headers,
        'body' => $body
    ));

    if (is_wp_error($response)) {
        return 'Error fetching access token';
    }

    $response_body = wp_remote_retrieve_body($response);
    $token_data = json_decode($response_body, true);
    $access_token = isset($token_data['access_token']) ? $token_data['access_token'] : '';

    // print_r($access_token);
    if (!$access_token) {
        return 'Error: No access token received';
    }

    $meeting_url = "https://api.zoom.us/v2/users/me/meetings";
    $meeting_headers = array(
        'Authorization' => 'Bearer ' . $access_token,
        'Content-Type' => 'application/json'
    );

    $meeting_body = json_encode(array(
        'topic' => 'Appointment with Doctor ID: ' . $doctor_id,
        'type' => 2, 
        'start_time' => $formatted_start_time,
        'duration' => 30, 
        'timezone' => 'UTC',
        'settings' => array(
            'join_before_host' => true,
            'participant_video' => true,
            'host_video' => true,
            'mute_upon_entry' => true,
            'waiting_room' => false
        )
    ));

    $meeting_response = wp_remote_post($meeting_url, array(
        'headers' => $meeting_headers,
        'body' => $meeting_body
    ));

    if (is_wp_error($meeting_response)) {
        return 'Error creating Zoom meeting';
    }

    $meeting_response_body = wp_remote_retrieve_body($meeting_response);
    $meeting_data = json_decode($meeting_response_body, true);
    // print_r($meeting_data);
    // if (isset($meeting_data['join_url'])) {
    //     return $meeting_data['join_url'];
    // } else {
    //     return 'Error: No meeting link generated';
    // }

     // Get the meeting ID and password
     $meeting_id = $meeting_data['id'];
     $meeting_password = $meeting_data['password'];
 
     // Construct the URL to open the meeting in the browser
     $browser_url = "https://zoom.us/wc/" . $meeting_id . "/join?pwd=" . $meeting_password;
 
     return $browser_url;
}
?>