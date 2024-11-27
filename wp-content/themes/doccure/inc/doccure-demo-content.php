<?php
function doccure_import_files() {
    return [
    
      [
        'import_file_name'             => 'Doccure Elementor',
        'categories'                   => '',
        'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/demo-elementor/content.xml',
        'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/demo-elementor/widgets.wie',
        'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/demo-elementor/doccure-export.dat',
        'import_preview_image_url'     => get_stylesheet_directory_uri() . '/demo-content/demo-elementor/screenshot.png',
        'preview_url'                  => 'https://doccure-wp.dreamstechnologies.com/elementor/',
'local_import_redux'           => [
        [
          'file_path'   => trailingslashit( get_template_directory() ) . 'demo-content/demo-elementor/redux.json',
          'option_name' => 'doccure_options',
        ],
      ],
      ],
      
    ];
  }
  add_filter( 'ocdi/import_files', 'doccure_import_files' );


  function doccure_after_import_setup() {
  // Assign menus to their locations.

      $privacymenu = get_term_by( 'name', 'privacymenu', 'nav_menu' );
      $Headerrightmenu = get_term_by( 'name', 'Headerrightmenu', 'nav_menu' );
      $Headertopmenu = get_term_by( 'name', 'Headertopmenu', 'nav_menu' );
      $homefivemenu = get_term_by( 'name', 'homefivemenu', 'nav_menu' );
      $Primary_Menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );

      set_theme_mod( 'nav_menu_locations', [
          'header-privacy-menu' => $privacymenu->term_id, 
          'my-custom-menu' => $Headerrightmenu->term_id, 
          'top-menu' => $Headertopmenu->term_id, 
          'header-homefive-menu' => $homefivemenu->term_id,
          'primary-menu' => $Primary_Menu->term_id,
          'mobile-menu' => $Primary_Menu->term_id,
        ]);

 
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
 
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );



   
 
}
add_action( 'ocdi/after_import', 'doccure_after_import_setup' );



function doccure_ocdi_slider_import( $selected_import ) {
  if ( 'Doccure' === $selected_import['import_file_name'] ) {

    // Set logo in customizer
    
  
  
  if (class_exists('revslider')) {
    $slider_array = array(
        get_template_directory() . "/sliders/slider-1.zip",
       
    );
  
    $slider = new RevSlider();
  
    foreach ($slider_array as $filepath) {
        $slider->importSliderFromPost(true, true, $filepath);
    }
  }
  }
}
add_action( 'ocdi/after_import', 'doccure_ocdi_slider_import' );



