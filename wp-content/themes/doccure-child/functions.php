<?php
//
// Recommended way to include parent theme styles.
// (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//

add_action( 'wp_enqueue_scripts', 'doccure_child_enqueue_styles', 10 );
function doccure_child_enqueue_styles() {

  $parenthandle = 'doccure-style';
  $theme = wp_get_theme();

  wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
      array('bootstrap'),  // if the parent theme code has a dependency, copy it to here
      $theme->parent()->get('Version')
  );
  wp_enqueue_style( 'doccure-child-style', get_stylesheet_directory_uri() . '/style.css',
      array( $parenthandle ),
      $theme->get('Version')
  );

}
// Your code goes below
