<?php

    
    /*** Emails ***/
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Emails', 'dreamsrent' ),
        'icon'       => 'fa fa-envelope',
        'fields'     => array(


            
            array(
                'title'  => __('"From name" in email', 'dreamsrent'),
                'subtitle'  => __('The name from who the email is received, by default it is your site name.', 'dreamsrent'),
                'id'    => 'emails_name',
                'default' =>  get_bloginfo( 'name' ),                
                'type'  => 'text',
            ),

            array(
                'title'  => __('"From" email ', 'dreamsrent'),
                'subtitle'  => __('This will act as the "from" and "reply-to" address. This emails should match your domain address', 'dreamsrent'),
                'id'    => 'emails_from_email',
                'default' =>  get_bloginfo( 'admin_email' ),               
                'type'  => 'text',
            ),
            array(
                'id'            => 'email_logo',
                'title'         => __( 'Logo for emails' , 'dreamsrent' ),
                'subtitle'   => __( 'Set here logo for emails, if nothing is set emails will be using default site logo', 'dreamsrent' ),
                'type'          => 'media',
                'default'       => '',
                'placeholder'   => ''
            ),
            
            array(
                'title' => __('<span style="font-size: 20px;">Registration/Welcome email for new users</span>', 'dreamsrent'),
                
                'type' => 'info',
                'id'   => 'header_welcome',
                'desc' => ''.__('Available tags are: ').'<strong>{user_mail}, {user_name}, {site_name}, {login}</strong>',
            ),
            // array(
            //     'title'      => __('Disable Welcome email to user (enabled by default)', 'dreamsrent'),
            //     'subtitle'      => __('Check this checkbox to disable sending emails to new users', 'dreamsrent'),
            //     'id'        => 'welcome_email_disable',
            //     'type'      => 'checkbox',
            // ), 

            array(
                'title'      => __('Enable Welcome email to user', 'dreamsrent'),
                'subtitle'   => __('Check this checkbox to enable sending forgot password emails', 'dreamsrent'),
                'id'         => 'welcome_email_enabled',
                'type'       => 'checkbox',
            ), 

            array(
                'title'      => __('Welcome Email Subject', 'dreamsrent'),
                'default'      => __('Welcome to {site_name}', 'dreamsrent'),
                'id'        => 'listing_welcome_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Welcome Email Content', 'dreamsrent'),
                'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
Welcome to our website.<br>
<ul>
<li>Username: {user_name}</li>
<li>Login: {login}</li>
</ul>
<br>
Thank you.
<br>")),
                'id'        => 'listing_welcome_email_content',
                'type'      => 'editor',
            ),   


           /*----------------*/

array(
    'title' =>  __('<span style="font-size: 20px;">Forgot Password Notification Email</span>', 'dreamsrent'),
    'type' => 'info',
    'id'   => 'forgot_password',
    'desc' => ''.__('Available tags are: ').'<strong>{user_name}, {reset_link}</strong>',

), 
array(
    'title'      => __('Enable Forgot Password Notification Email', 'dreamsrent'),
    'subtitle'   => __('Check this checkbox to enable sending forgot password emails', 'dreamsrent'),
    'id'         => 'forgot_password_email_enabled',
    'type'       => 'checkbox',
), 
array(
    'title'      => __('Forgot Password Notification Email Subject', 'dreamsrent'),
    'default'    => __('Password Reset Request', 'dreamsrent'),
    'id'         => 'forgot_password_email_subject',
    'type'       => 'text',
),
    array(
        'title'      => __('Forgot Password Notification Email Content', 'dreamsrent'),
        'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {user_name},</p>
<p>We received a request to reset your password. If you requested this change, please click the link below to reset your password:</p>
 
 <div class='button'>
<a href='{reset_link}'>Reset Password</a><br>
</div>
 
<p>If you did not request a password reset, please ignore this email.</p>
 
<p>Thank you.</p>
 ")),
        'id'         => 'forgot_password_email_content',
        'type'       => 'editor',
    ),
 

  
  /*----------------*/

    array(
        'title' => __('<span style="font-size: 20px;">New Booking Notification Email For Doctor</span>', 'dreamsrent'),
        'type' => 'info',
        'id'   => 'new_order_notification',
        'desc' => ''.__('Available tags are: ').'<strong>{doctor_name},{patient_name},{appointment_date},{appointment_time},{consultant_fee},{total_price},{description},{booking_details}</strong>',
    ), 
    array(
        'title'      => __('Enable New Booking Notification Email', 'dreamsrent'),
        'subtitle'   => __('Check this checkbox to enable sending new order emails', 'dreamsrent'),
        'id'         => 'new_order_email_enabled',
        'type'       => 'checkbox',
    ), 
    array(
        'title'      => __('New Booking Notification Email Subject', 'dreamsrent'),
        'default'    => __('Your Booking Confirmation', 'dreamsrent'),
        'id'         => 'new_order_email_subject',
        'type'       => 'text',
    ),
    array(
        'title'      => __('New Booking Notification Email Content', 'dreamsrent'),
        'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {doctor_name},</p>

<p>{patient_name} is request you for appoinment in hospital {doctor_name} on date  {appointment_date} at {appointment_time} </p>

{booking_details}
>")),
        'id'         => 'new_order_email_content',
        'type'       => 'editor',
    ),

  /*----------------*/
  array(
    'title' => __('<span style="font-size: 20px;">New Booking Notification Email For Patient</span>', 'dreamsrent'),
    'type' => 'info',
    'id'   => 'new_order_notification_patient',
    'desc' => ''.__('Available tags are: ').'<strong>{doctor_name},{patient_name},{appointment_date},{appointment_time},{consultant_fee},{total_price},{description},{booking_details}</strong>',   ), 
array(
    'title'      => __('Enable New Booking Notification Email', 'dreamsrent'),
    'subtitle'   => __('Check this checkbox to enable sending new order emails', 'dreamsrent'),
    'id'         => 'new_order_email_enabled_patient',
    'type'       => 'checkbox',
), 
array(
    'title'      => __('New Booking Notification Email Subject For', 'dreamsrent'),
    'default'    => __('Your Booking Confirmation', 'dreamsrent'),
    'id'         => 'new_order_email_subject_patient',
    'type'       => 'text',
),
array(
    'title'      => __('New Booking Notification Email Content', 'dreamsrent'),
    'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {patient_name},</p>

<p>Thank you for your booking! We are excited to fulfill it. Here are the details of your booking:</p>

{booking_details}

<p>You can view your booking and track its status by logging into your account.</p>

<p>If you have any questions, feel free to contact us.</p>

<p>Thank you for shopping with us!</p>")),
    'id'         => 'new_order_email_content_patient',
    'type'       => 'editor',
),



  /*----------------*/
  array(
    'title' => __('<span style="font-size: 20px;">Approved Notifications</span>', 'dreamsrent'),
    'type' => 'info',
    'id'   => 'new_order_notification_approve',
    'desc' => ''.__('Available tags are: ').'<strong>{doctor_name},{patient_name},{appointment_date},{appointment_time},{consultant_fee},{total_price},{booking_details}</strong>',   ), 
array(
    'title'      => __('Enable Approved Notification Email', 'dreamsrent'),
    'subtitle'   => __('Check this checkbox to enable sending new order emails', 'dreamsrent'),
    'id'         => 'new_order_email_enabled_approve',
    'type'       => 'checkbox',
), 
array(
    'title'      => __('Approved Notification Email Subject For', 'dreamsrent'),
    'default'    => __('Your Approved Confirmation', 'dreamsrent'),
    'id'         => 'new_order_email_subject_approve',
    'type'       => 'text',
),
array(
    'title'      => __('Approved Notification Email Content', 'dreamsrent'),
    'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {patient_name},</p>

<p>{doctor_name} is approved to your appoinment on date {appointment_date} at {appointment_time}</p>

{booking_details}
")),
    'id'         => 'new_order_email_content_approve',
    'type'       => 'editor',
),
   

/*----------------*/
array(
    'title' => __('<span style="font-size: 20px;">Cancelled Notifications</span>', 'dreamsrent'),
    'type' => 'info',
    'id'   => 'new_order_notification_cancel',
    'desc' => ''.__('Available tags are: ').'<strong>{doctor_name},{patient_name},{appointment_date},{appointment_time},{consultant_fee},{total_price},{booking_details}</strong>',   ), 
array(
    'title'      => __('Enable Cancelled Notification Email', 'dreamsrent'),
    'subtitle'   => __('Check this checkbox to enable sending new order emails', 'dreamsrent'),
    'id'         => 'new_order_email_enabled_cancel',
    'type'       => 'checkbox',
), 
array(
    'title'      => __('Cancelled Notification Email Subject For', 'dreamsrent'),
    'default'    => __('Your Cancelled Confirmation', 'dreamsrent'),
    'id'         => 'new_order_email_subject_cancel',
    'type'       => 'text',
),
array(
    'title'      => __('Cancelled Notification Email Content', 'dreamsrent'),
    'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {patient_name},</p>

<p>{doctor_name} is cancelled to your appoinment with {doctor_name} on date {appointment_date} at {appointment_time}</p>

{booking_details}
")),
    'id'         => 'new_order_email_content_cancel',
    'type'       => 'editor',
),



/*----------------*/
array(
    'title' => __('<span style="font-size: 20px;">Reschedule Notifications By Doctor</span>', 'dreamsrent'),
    'type' => 'info',
    'id'   => 'new_order_notification_redoc',
    'desc' => ''.__('Available tags are: ').'<strong>{doctor_name},{patient_name},{appointment_date},{appointment_time},{consultant_fee},{total_price},{booking_details}</strong>',   ), 
array(
    'title'      => __('Enable Reschedule Notification Email', 'dreamsrent'),
    'subtitle'   => __('Check this checkbox to enable sending new order emails', 'dreamsrent'),
    'id'         => 'new_order_email_enabled_redoc',
    'type'       => 'checkbox',
), 
array(
    'title'      => __('Reschedule Notification Email Subject For', 'dreamsrent'),
    'default'    => __('Your Reschedule Confirmation', 'dreamsrent'),
    'id'         => 'new_order_email_subject_redoc',
    'type'       => 'text',
),
array(
    'title'      => __('Reschedule Notification Email Content', 'dreamsrent'),
    'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {patient_name},</p>

<p>{doctor_name} is Reschedule to booking appoinment on date {appointment_date} at {appointment_time}</p>

{booking_details}
")),
    'id'         => 'new_order_email_content_redoc',
    'type'       => 'editor',
),


/*----------------*/
array(
    'title' => __('<span style="font-size: 20px;">Reschedule Notifications By Patient</span>', 'dreamsrent'),
    'type' => 'info',
    'id'   => 'new_order_notification_repat',
    'desc' => ''.__('Available tags are: ').'<strong>{doctor_name},{patient_name},{appointment_date},{appointment_time},{consultant_fee},{total_price},{booking_details}</strong>',   ), 
array(
    'title'      => __('Enable Reschedule Notification Email', 'dreamsrent'),
    'subtitle'   => __('Check this checkbox to enable sending new order emails', 'dreamsrent'),
    'id'         => 'new_order_email_enabled_repat',
    'type'       => 'checkbox',
), 
array(
    'title'      => __('Reschedule Notification Email Subject For', 'dreamsrent'),
    'default'    => __('Your Reschedule Confirmation', 'dreamsrent'),
    'id'         => 'new_order_email_subject_repat',
    'type'       => 'text',
),
array(
    'title'      => __('Reschedule Notification Email Content', 'dreamsrent'),
    'default'    => trim(preg_replace('/\t+/', '', "<p>Hi {doctor_name},</p>

<p>{patient_name} is Reschedule to booking appoinment on date {appointment_date} at {appointment_time}</p>

{booking_details}
")),
    'id'         => 'new_order_email_content_repat',
    'type'       => 'editor',
),

)
) );
 /*----------------*/