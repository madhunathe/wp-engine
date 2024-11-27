<?php

if ( !defined( 'ABSPATH' ) ) exit;

class DZ_Main_Controller {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
    }

    public static function add_admin_menu() {
        add_menu_page(
            'Zoom Integration', 
            'Zoom',             
            'manage_options',   
            'dz_zoom_integration',
            array( __CLASS__, 'main_page' ),
            'dashicons-video-alt3'
        );

        add_submenu_page(
            'dz_zoom_integration',
            'Zoom Meetings',      
            'Meetings',           
            'manage_options',     
            'dz_zoom_meetings',   
            array( __CLASS__, 'meetings_page' )
        );

        // Add a custom page for editing meetings
    // add_submenu_page(
    //     'dz_zoom_integration', 
    //     'Edit Meeting', 
    //     'Edit Meeting', 
    //     'manage_options', 
    //     'meetings-page-edit',
    //     array( __CLASS__, 'edit_meeting_page' )
    // );

        // add_submenu_page(
        //     'dz_zoom_integration', 
        //     'Zoom Settings',    
        //     'Settings',        
        //     'manage_options',    
        //     'dz_zoom_settings', 
        //     array( __CLASS__, 'settings_page' )
        // );

        add_submenu_page(
            'dz_zoom_integration', 
            'Calendar Bookings',    
            'Calendar Bookings',        
            'manage_options',    
            'dz_zoom_calendar', 
            array( __CLASS__, 'calendar_page' )
        );
     
    }

    public static function main_page() {
        include DZI_PLUGIN_DIR . 'includes/views/main-page.php';
    }

    public static function calendar_page() {
        include DZI_PLUGIN_DIR . 'includes/views/calendar-page.php';
    }

    // public static function meetings_page() {
    //     include DZI_PLUGIN_DIR . 'includes/views/meetings-page.php';
    //  }


    public static function meetings_page() {
        // Ensure the user has the appropriate permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Sorry, you are not allowed to access this page.' );
        }
    
        // Check for an action parameter to determine which sub-page to show
        $action = isset($_GET['action']) ? $_GET['action'] : 'views'; // Default to 'list'
    
        switch ( $action ) {
            // case 'edit':
            //     // Include the edit page
            //     include DZI_PLUGIN_DIR . 'includes/views/meetings-page-edit.php';
            //     break;
    
            case 'views':
                // Include the view page
                include DZI_PLUGIN_DIR . 'includes/views/meetings-page.php';
                break;

                case 'view':
                    // Include the view page
                    include DZI_PLUGIN_DIR . 'includes/views/meetings-page-view.php';
                    break;
    
             default:
                // Include the default list page
                include DZI_PLUGIN_DIR . 'includes/views/meetings-page.php';
                break;
        }
    }
    

    // public static function settings_page() {
    //     include DZI_PLUGIN_DIR . 'includes/views/settings-page.php';
    // }

    // public static function edit_meeting_page() {
    //     // Ensure the user has the appropriate permissions
    //     if ( ! current_user_can( 'manage_options' ) ) {
    //         wp_die( 'Sorry, you are not allowed to access this page.' );
    //     }
    
    //     // Check if an ID is passed and sanitize it
    //     $meeting_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    //     // Include the edit form
    //     include DZI_PLUGIN_DIR . 'includes/views/meetings-page-edit.php';
    // }


    public static function activate() {
        // Activation code here
    }

    public static function deactivate() {
        // Deactivation code here
    }


    
}