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
    <h1>Edit Meeting</h1>
    <form method="POST">
        <input type="hidden" name="meeting_id" value="<?php echo esc_attr($meeting->id); ?>">
        <p>
            <label for="doctor_id">Doctor</label><br>
            <select name="doctor_id" id="doctor-id">
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo esc_attr($customer->ID); ?>" <?php selected($meeting->doctor_id, $customer->ID); ?>>
                        <?php echo esc_html($customer->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="patient_id">Patient</label><br>
            <select name="patient_id" id="patient-id">
                <?php foreach ($regular_users as $regular_user): ?>
                    <option value="<?php echo esc_attr($regular_user->ID); ?>" <?php selected($meeting->patient_id, $regular_user->ID); ?>>
                        <?php echo esc_html($regular_user->display_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="appointment_date">Appointment Date</label><br>
            <input type="text" name="appointment_date" id="appointment-date" value="<?php echo esc_attr($meeting->appointment_date); ?>">
        </p>
        <p>
            <input type="submit" name="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>
<?php
// Handle form submission
if (isset($_POST['submit'])) {
    $new_doctor_id = intval($_POST['doctor_id']);
    $new_patient_id = intval($_POST['patient_id']);
    $new_appointment_date = sanitize_text_field($_POST['appointment_date']); // Sanitize date input

    // Update the database
    $wpdb->update(
        $table_name,
        array(
            'doctor_id' => $new_doctor_id,
            'patient_id' => $new_patient_id,
            'appointment_date' => $new_appointment_date // Update the date correctly
        ),
        array('id' => $meeting_id),
        array('%d', '%d', '%s'),
        array('%d')
    );

    

    wp_redirect(admin_url('admin.php?page=dz_zoom_meetings&status=success'));
    exit;

}
?>