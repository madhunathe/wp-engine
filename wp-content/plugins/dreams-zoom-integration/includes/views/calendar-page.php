<?php
global $wpdb;

$results = $wpdb->get_results("
    SELECT zl.*, u.display_name as patient_name 
    FROM {$wpdb->prefix}zoom_links zl
    LEFT JOIN {$wpdb->prefix}users u ON zl.patient_id = u.ID
");

$events = array();
foreach ($results as $result) {
    $doctor_name = get_the_title($result->doctor_id); 
    $patient_name = $result->patient_name; 
    $appointment_date = $result->appointment_date;
    $time_slot = $result->slots;
    
    $time_parts = explode('-', $time_slot);
    $start_time = $time_parts[0];
    $end_time = $time_parts[1];

    $start_time_formatted = substr($start_time, 0, 2) . ':' . substr($start_time, 2, 2);
    $end_time_formatted = substr($end_time, 0, 2) . ':' . substr($end_time, 2, 2);

    $events[] = array(
        'title' => $doctor_name,
        'start' => $appointment_date,
        'extendedProps' => array(
            'doctor_name' => $doctor_name,
            'patient_name' => $patient_name,
            'appointment_date' => $appointment_date,
            'time_slot' => $start_time_formatted . ' - ' . $end_time_formatted,
        ),
    );
}

$events_json = json_encode($events);
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div id="calendar"></div>
</div>

<script>
    var calendarEvents = <?php echo $events_json; ?>;

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: calendarEvents,
            eventDidMount: function(info) {
                var popoverContent = `
                    <strong>Doctor:</strong> ${info.event.extendedProps.doctor_name}<br>
                    <strong>Patient:</strong> ${info.event.extendedProps.patient_name}<br>
                    <strong>Date:</strong> ${info.event.extendedProps.appointment_date}<br>
                    <strong>Time:</strong> ${info.event.extendedProps.time_slot}
                `;

                var popover = new bootstrap.Popover(info.el, {
                    title: info.event.title,
                    content: popoverContent,
                    html: true,
                    placement: 'top',
                    trigger: 'hover'
                });
            }
        });
        calendar.render();
    });
</script>
