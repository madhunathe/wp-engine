<?php
// Ensure the user has the appropriate permissions
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Sorry, you are not allowed to access this page.' );
}

// Fetch the meeting ID from the query parameters
$meeting_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the meeting details from the database
global $wpdb;
$table_name = $wpdb->prefix . 'zoom_links';
$meeting = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $meeting_id));

if (!$meeting) {
    echo '<div class="notice notice-error"><p>Meeting not found.</p></div>';
    return;
}

// Fetch all customers for the dropdown
$regular_users = get_users(array('role' => 'regular_users'));
$customers = get_posts(array(
    'post_type' => 'doctors', // Replace with your actual post type
));
?>
<div class="wrap">
    <h1><?php echo esc_html('View Meeting', 'doccure_zoom'); ?></h1>
    <p><strong>Doctor:</strong> <?php echo esc_html(doccure_full_name($meeting->doctor_id)); ?></p>
    <p><strong>Patient:</strong> <?php echo esc_html(get_userdata($meeting->patient_id)->display_name); ?></p>
    <p><strong>Meeting ID:</strong> <?php echo esc_html($meeting->meeting_id); ?></p>
    <p><strong>Appointment Date:</strong> <?php echo esc_html($meeting->appointment_date); ?></p>
    <p><a href="<?php echo esc_url(admin_url('admin.php?page=dz_zoom_meetings')); ?>" class="button">Back to Meetings</a></p>
</div>