<?php
add_action('wp_ajax_nopriv_connect_to_zoom', 'connect_to_zoom_callback');
add_action('wp_ajax_connect_to_zoom', 'connect_to_zoom_callback');

function connect_to_zoom_callback() {
    check_ajax_referer('doccure_zoom_connect_nonce', 'nonce');

    $client_id = isset($_POST['client_id']) ? sanitize_text_field($_POST['client_id']) : '';
    $client_secret = isset($_POST['client_secret']) ? sanitize_text_field($_POST['client_secret']) : '';

    if (empty($client_id) || empty($client_secret)) {
        wp_send_json_error(array('message' => 'Zoom Client ID or Client Secret is missing'));
        return;
    }

    $access_token = doccure_zoom_api_connect($client_id, $client_secret);

    if ($access_token) {
        wp_send_json_success(array('message' => 'Connected to Zoom successfully', 'access_token' => $access_token));
    } else {
        wp_send_json_error(array('message' => 'Error connecting to Zoom API'));
    }
}

function doccure_zoom_api_connect($client_id, $client_secret) {
  $endpoint = 'https://zoom.us/oauth/token';
  $headers = array(
      'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret),
      'Content-Type' => 'application/x-www-form-urlencoded',
  );

  $body = array(
      'grant_type' => 'client_credentials',
  );

  $token_response = wp_remote_post($endpoint, array(
      'method' => 'POST',
      'headers' => $headers,
      'body' => http_build_query($body),
  ));

  if (is_wp_error($token_response)) {
      error_log('Error connecting to Zoom: ' . $token_response->get_error_message());
      return false;
  }

  if (wp_remote_retrieve_response_code($token_response) === 200) {
      $response_body = json_decode(wp_remote_retrieve_body($token_response), true);
      if (isset($response_body['access_token'])) {
          return $response_body['access_token'];
      } else {
          error_log('Zoom API response missing access_token');
          return false;
      }
  } else {
      error_log('Zoom API returned an error: ' . wp_remote_retrieve_body($token_response));
      return false;
  }
}

if (class_exists('Redux')) {
  class ReduxFramework_button {
    public $args = array();
    public $field = array();
    public $value = '';
    public $parent = null;

    public function __construct($field = array(), $value = '', $parent = null) {
      $this->field = $field;
      $this->value = $value;
      $this->parent = $parent;
    }

    public function render() {
      $id = $this->field['id'];
      $button_text = isset($this->field['text']) ? $this->field['text'] : 'Click me';
      echo '<button id="' . esc_attr($id) . '" class="button-primary">' . esc_html($button_text) . '</button>';
      echo '<span id="zoom_connection_status" style="margin-left: 10px;"></span>';
    }
  }

  Redux::setExtensions('YOUR_OPT_NAME', 'YOUR_PATH_TO_EXTENSION_FOLDER');
}

function doccure_zoom_enqueue_scripts() {
    wp_enqueue_script(
        'doccure-zoom',
        plugins_url('/assets/js/doccure-zoom.js', dirname(__FILE__)),
        array('jquery'),
        null,
        true
    );
    wp_localize_script('doccure-zoom', 'doccureZoom', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('doccure_zoom_connect_nonce'),

    ));


}
add_action('admin_enqueue_scripts', 'doccure_zoom_enqueue_scripts');
?>