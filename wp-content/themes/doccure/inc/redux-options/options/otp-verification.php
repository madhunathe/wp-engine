<?php 


    // OTP Verification Process
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Verification', 'dreamsrent' ),
        'icon'       => 'fa fa-shield-halved',
        'fields'     => array(

            array(
                'id'       => 'otp_switch',
                'type'     => 'switch',
                'title'    => __('Email OTP Option', 'dreamsrent'),
                'default'  => true,
                'on'       => __('On', 'dreamsrent'),
                'off'      => __('Off', 'dreamsrent'),
            ),
            
            array(
                'title'  => __('"From name" in email', 'dreamsrent'),
                'subtitle'  => __('The name from who the email is received, by default it is your site name.', 'dreamsrent'),
                'id'    => 'otp_emails_name',
                'default' =>  get_bloginfo( 'name' ),                
                'type'  => 'text',
            ),

            array(
                'title'  => __('"From" email ', 'dreamsrent'),
                'subtitle'  => __('This will act as the "from" and "reply-to" address. This emails should match your domain address', 'dreamsrent'),
                'id'    => 'otp_emails_from_email',
                'default' =>  get_bloginfo( 'admin_email' ),               
                'type'  => 'text',
            ),

            array(
                'title'  => __('"Subject" email ', 'dreamsrent'),
                'subtitle'  => __('This will act as the Subject of the email.', 'dreamsrent'),
                'id'    => 'otp_subject_from_email',
                'default' =>  'Your Confirmation Code',               
                'type'  => 'text',
            ),

            array(
                'id'            => 'otp_email_logo',
                'title'         => __( 'Logo for emails' , 'dreamsrent' ),
                'subtitle'   => __( 'Set here logo for emails, if nothing is set emails will be using default site logo', 'dreamsrent' ),
                'type'          => 'media',
                'default'       => '',
                'placeholder'   => ''
            ),

            array(
                'title' => __('<span style="font-size: 20px;">Email OTP Verification</span>', 'dreamsrent'),
                
                'type' => 'info',
                'id'   => 'otp_available_tags',
                'desc' => ''.__('Available tags are: ').'<strong>{otp}, {user_email}</strong>',
            ),

            array(
                'title'  => __('"Message" email ', 'dreamsrent'),
                'subtitle'  => __('This will act as the email content.', 'dreamsrent'),
                'id'    => 'otp_content_from_email',
                'default'      => trim(preg_replace('/\t+/', '', "
                <p>Hi {user_email},</p>
                <p>Enter the code below and verify the user email</p>
                <p>{otp}</p>
                <p>Use the above code to proceed with confirmation in the app or online.</p>")),                
                'type'  => 'editor',
            ),

            
        ),));