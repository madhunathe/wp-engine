<?php 

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
            <form method="POST" style="display:inline;">
                <input type="hidden" name="meeting_id" value="' . esc_attr($row->id) . '">
                <button type="button" class="button edit-button" data-id="' . esc_attr($row->id) . '"
                    data-doctor-id="' . esc_attr($row->doctor_id) . '"
                    data-patient-id="' . esc_attr($row->patient_id) . '"
                    data-appointment-date="' . esc_attr($row->appointment_date) . '">Edit</button>
            </form>
        </td>
        </tr>';
    }

    echo '</tbody></table>';

    // Fetch all customers for the dropdown
    $regular_users = get_users(array('role' => 'regular_users'));
    $customers = get_posts(array(
        'post_type' => 'doctors', // Replace with your actual post type
    ));

    // Edit form (hidden by default)
    echo '
    <div id="edit-meeting-form" style="display:none;">
        <h2>Edit Meeting</h2>
        <form method="POST">
            <input type="hidden" name="meeting_id" id="meeting-id">
            <p>
                <label for="doctor_id">Doctor</label><br>
                <select name="doctor_id" id="doctor-id">';
                
                foreach ($customers as $customer) {
                    echo '<option value="' . esc_attr($customer->ID) . '">' . esc_html($customer->post_title) . '</option>';
                }

            echo '</select>
            </p>

            <p>
                <label for="patient_id">Patient</label><br>
                <select name="patient_id" id="patient-id">';
                
                foreach ($regular_users as $regular_user) {
                    echo '<option value="' . esc_attr($regular_user->ID) . '">' . esc_html($regular_user->display_name) . '</option>';
                }

            echo '</select>
            </p>

            <p>
                <label for="appointment_date">Appointment Date</label><br>
                <input type="text" name="appointment_date" id="appointment-date">
            </p>
            <p>
                <input type="submit" name="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>
    </div>';

    // Add JavaScript to handle the Edit button click
    echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var editButtons = document.querySelectorAll(".edit-button");
            var editForm = document.getElementById("edit-meeting-form");

            editButtons.forEach(function(button) {
                button.addEventListener("click", function() {
                    var meetingId = this.getAttribute("data-id");
                    var doctorId = this.getAttribute("data-doctor-id");
                    var patientId = this.getAttribute("data-patient-id");
                    var appdate = this.getAttribute("data-appointment-date");

                    // Fill the form with current data
                    document.getElementById("meeting-id").value = meetingId;
                    document.getElementById("doctor-id").value = doctorId;
                    document.getElementById("patient-id").value = patientId;
                    document.getElementById("appointment-date").value = appdate;

                    // Show the edit form
                    editForm.style.display = "block";
                    window.scrollTo(0, editForm.offsetTop);
                });
            });
        });
    </script>';
}

display_zoom_meeting_ids();
?>
<div class="wrap">
    <h1><?php echo esc_html('Zoom Meetings', 'doccure_zoom'); ?></h1>
    <p><?php echo esc_html('Manage your Zoom meetings here.', 'doccure_zoom'); ?></p>
</div>