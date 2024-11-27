<?php
/**
 * Template part for header layout 10
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
  $top_header_width_class = (doccure_get_option('adjust-custom-header-top-width') == true && doccure_get_option('header-top-width-style') == 'full-width') ? 'container-fluid' : 'container';
 $header_controls_style = doccure_get_option('header_controls_style');

 global $doccure_options;
$is_register	= !empty( $doccure_options['registration_form'] ) ? $doccure_options['registration_form'] : '';
 $is_login		= !empty( $doccure_options['login_form'] ) ? $doccure_options['login_form'] : '';
 $user_registration		= !empty( $doccure_options['user_registration'] ) ? $doccure_options['user_registration'] : '';

 
 ?> 

 <div class="doccure_header-middle">
 
      <div class="container">
 
   
     <div class="navbar">
       <?php
          // Site logo
         get_template_part( 'template-parts/header/elements/logo' );
         // nav menu
         doccure_nav_menu();
         if ( is_active_sidebar( 'custom-language-widget' ) ) : ?>
          <div id="lang-widget-area" class="lang-chw-widget-area widget-area" role="complementary">
          <?php dynamic_sidebar( 'custom-language-widget' ); ?>
          </div>
           <?php endif; 
if ( is_active_sidebar( 'custom-header-widget' ) ) : ?>
 
     <?php endif; 
         // controls
         get_template_part( 'template-parts/header/elements/controls' );
         

        //  if(doccure_get_option('display-cta-button')) {
        //    // main cta button
        //    get_template_part('template-parts/header/elements/main-cta-button') ;
        //  }
        ?>


        <?php
         global $current_user;
		wp_get_current_user();

    if (is_user_logged_in()) { ?>


    <?php
    } else { 
      
      if(doccure_get_option('display-cta-button')) {
        
 $header_cta_btn_title = doccure_get_option('header_cta_btn_title');
$header_cta_btn_link = doccure_get_option('header_cta_btn_link');
if (!$header_cta_btn_title || !$header_cta_btn_link) {
    return;
}

$header_cta_rbtn_title = doccure_get_option('header_cta_rbtn_title');
$header_cta_rbtn_link = doccure_get_option('header_cta_rbtn_link');
if (!$header_cta_rbtn_title || !$header_cta_rbtn_link) {
    return;
}

?>
     
<?php if( !empty( $user_registration ) ){ ?>
<ul class="nav header-navbar-rht">

<?php if( !empty( $is_register ) ){ ?>
<li class="register-btn">
<a href="<?php echo esc_html($header_cta_rbtn_link); ?>" class="btn reg-btn"><i class="feather-user"></i><?php echo esc_html($header_cta_rbtn_title); ?></a>
</li>
<?php } ?>
<?php if( !empty( $is_login ) ){ ?>
<li class="register-btn">
<a href="<?php echo esc_html($header_cta_btn_link); ?>" class="btn btn-primary log-btn"><i class="feather-lock"></i><?php echo esc_html($header_cta_btn_title); ?></a>
</li>
<?php } ?>
</ul>
<?php } }  } ?>


		<?php	if (is_user_logged_in()) {
			?>

<div class="nav-item dropdown">
		<a href="javascript:void(0)">
		<span class="user-img sss">
       <?php  echo get_avatar( get_current_user_id(), 80 ); ?>

		</span>
		</a>
		<div class="dropdown-menu-end">
		<div class="user-header">
		<div class="avatar avatar-sm">
       <?php echo get_avatar( get_current_user_id(), 80 ); ?>

		</div>
		<div class="user-text">
		<h6><?php  echo  esc_html($current_user->user_login); ?></h6>
		<p class="text-success mb-0">
			<?php 
			$role = $current_user->roles[0];
			$role_name = $role ? wp_roles()->get_names()[ $role ] : '';
			echo esc_html($role_name);
			?>
		</p>
		</div>
		</div>
		<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('insights', get_current_user_id()); ?>" class="dropdown-item" href="<?php echo home_url();?>/dashboard"><?php esc_html_e('Dashboard', 'doccure'); ?></a>
		
		<a class="dropdown-item" href="<?php echo wp_logout_url( home_url() ); ?>"><?php esc_html_e('Logout', 'doccure'); ?></a>
		</div>
</div>
			<?php } ?>  
     </div>
   </div>
 </div>
