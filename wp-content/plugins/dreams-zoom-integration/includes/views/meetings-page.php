<div class="wrap">
    <h1><?php echo esc_html('Zoom Meetings', 'doccure_zoom'); ?></h1>
    <p><?php echo esc_html('Manage your Zoom meetings here.', 'doccure_zoom'); ?></p>
</div>

<?php 
     if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<div class="notice notice-success is-dismissible"><p>Meeting updated successfully.</p></div>';
}
function display_zoom_meeting_ids() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zoom_links';

    // Handle the form submission
    if (isset($_POST['submit'])) {
        $meeting_id = intval($_POST['meeting_id']);
        $new_doctor_id = intval($_POST['doctor_id']);
        $new_patient_id = intval($_POST['patient_id']);
        $new_appointment_id = sanitize_text_field($_POST['appointment_date']); // Sanitize date input
        
        // Update the database
        $wpdb->update(
            $table_name,
            array(
                'doctor_id' => $new_doctor_id,
                'patient_id' => $new_patient_id,
                'appointment_date' => $new_appointment_id // Update the date correctly
            ),
            array('id' => $meeting_id),
            array('%d', '%d', '%s'),
            array('%d')
        );

        echo '<div class="notice notice-success is-dismissible"><p>Meeting updated successfully.</p></div>';
    }

    // Fetch the meeting data from the table
    $results = $wpdb->get_results("SELECT * FROM $table_name");
  
    // Display the data in a table format
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>
    <th>' . esc_html('ID', 'doccure_zoom') . '</th>
    <th>' . esc_html('Doctor', 'doccure_zoom') . '</th>
    <th>' . esc_html('Patient', 'doccure_zoom') . '</th>
    <th>' . esc_html('Meeting ID', 'doccure_zoom') . '</th>
    <th>' . esc_html('Appointment Date', 'doccure_zoom') . '</th>
    <th>' . esc_html('Action', 'doccure_zoom') . '</th>
    </tr>
    </thead>';
    echo '<tbody>';

    foreach ($results as $row) {
        $doctor_name = doccure_full_name($row->doctor_id);
        $patient_name_get = get_userdata($row->patient_id);
        $patient_name = $patient_name_get ? $patient_name_get->display_name : 'N/A';

        echo '<tr>
        <td>' . esc_html($row->id) . '</td>
        <td>' . esc_html($doctor_name) . '</td>
        <td>' . esc_html($patient_name) . '</td>
        <td>' . esc_html($row->meeting_id) . '</td>
        <td>' . esc_html($row->appointment_date) . '</td>
        <td>
            <a href="' . esc_url(admin_url('admin.php?page=dz_zoom_meetings&action=view&id=' . $row->id)) . '" class="button">View</a>
         </td>
        </tr>';
    }

    echo '</tbody></table>';
}

display_zoom_meeting_ids();
?>

