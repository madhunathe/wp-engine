<?php
/**
 * @File Type	General Options for pages, posts and custom post type
 * @package	 	WordPress
 * @link 		https://themeforest.net/user/dreamstechnologies
 */

// die if accessed directly
if (!defined('ABSPATH')) {
    die('no kiddies please!');
}

global $wp_registered_sidebars,$doccure_options;

$seo_option	= !empty($doccure_options['enable_seo']) ? $doccure_options['enable_seo'] : '';

$form_el = new AMetaboxes();
$am_sidebarsArray		= array();
$am_sidebarsArray[''] 	= esc_html__('No Sidebar','doccure_core');
$sidebars = $wp_registered_sidebars;
if (is_array($sidebars) && !empty($sidebars)) {
    foreach ($sidebars as $key => $sidebar) {
        $am_sidebarsArray[$key] = $sidebar['name'];
    }
}

$revsliders		= array();
$revsliders[0] 	= esc_html__('Select a slider','doccure_core');
if (class_exists('RevSlider')) {
	if( function_exists('doccure_prepare_rev_slider') ){
		$revsliders	= doccure_prepare_rev_slider();
	}	
}

$form_el = new AMetaboxes();
$menu	= array();
if(!empty($seo_option)){
$menu[]	= array( esc_html__('Seo', 'doccure_core') , 'seo' , 'pushpin' , true );
}
$menu[]	= array( esc_html__('Sidebar', 'doccure_core') , 'sidebar' , 'pushpin' , false );
$menu[]	= array( esc_html__('Page Title Bar', 'doccure_core') , 'pagetitlebar' , 'pushpin' , false );
$menu[]	= array( esc_html__('Header', 'doccure_core') , 'header' , 'pushpin' , false );
?>
<div class="dc-main-metaoptions">
	<div class="am_option_tabs">
		<ul><?php $form_el->form_process_general_menu($menu); ?></ul>
	</div>
	<div class='am_metabox'>
		<?php if(!empty($seo_option)){ ?>
			<div id="am_seo_tab">
				<?php
					$form_el->form_process_text(
							array('name' 	=> esc_html__('Seo Title','doccure_core'),
								'id' 		=> 'seo_title',
								'std' 		=> '',
								'desc' 		=> esc_html__('','doccure_core'),
								'meta' 		=> ''
							)
					);

					$form_el->form_process_textarea(
							array('name' 	=> esc_html__('Seo Description','doccure_core'),
								'id' 		=> 'seo_description',
								'std' 		=> '',
								'desc' 		=> esc_html__('','doccure_core'),
								'meta' 		=> ''
							)
					);

					$form_el->form_process_text(
							array('name' 	=> esc_html__('Seo Keywords','doccure_core'),
								'id' 		=> 'seo_keywords',
								'std' 		=> '',
								'desc' 		=> esc_html__('','doccure_core'),
								'meta' 		=> ''
							)
					);
				?>
			</div>
		<?php } ?>
		<div id="am_sidebar_tab" style="display:none" >
		<?php
			$form_el->form_process_select(
					array('name' 	=> esc_html__('Layout','doccure_core'),
						'id' 		=> 'layout',
						'std' 		=> 'no_sidebar',
						'desc' 		=> esc_html__('Select sidebar layout to display sidebar on this page.','doccure_core'),
						'options' 	=> array(
							'default' 			=> esc_html__('Default Setting','doccure_core'),
							'no_sidebar' 		=> esc_html__('No Sidebar','doccure_core'),
							'left_sidebar' 		=> esc_html__('Left Sidebar','doccure_core'),
							'right_sidebar' 	=> esc_html__('Right Sidebar','doccure_core'),
						)
					)
			);

			$form_el->form_process_select(
					array('name' 	=> esc_html__('Sidebar','doccure_core'),
						'id' 		=> 'left_sidebar',
						'std' 		=> '',
						'desc' 		=> esc_html__('Choose left sidebar.','doccure_core'),
						'options' 	=> $am_sidebarsArray
					)
			);
		?>
		</div>

		<div id="am_pagetitlebar_tab" style="display:none" > 
		<?php
			$form_el->form_process_select(
					array('name' 	=> esc_html__('Page Title','doccure_core'),
						'id' 		=> 'page_title',
						'std' 		=> 'show',
						'desc' 		=> '',
						'options' => array(
							'hide' 	=> esc_html__('Hide it','doccure_core'),
							'show'	=> esc_html__('Show it','doccure_core'),
						)
					)
			);
			$form_el->form_process_select(
					array('name' 	=> esc_html__('Page Title Bar','doccure_core'),
						'id' 		=> 'title_bar',
						'std' 		=> 'default',
						'desc' 		=> '',
						'options' => array(
							'default' 	=> esc_html__('Default Setting','doccure_core'),
							'custom'	=> esc_html__('Custom Settings','doccure_core'),
							///'rev' 		=> esc_html__('Revolution Slider','doccure_core'),
							'shortcode'	=> esc_html__('Custom Shortcode','doccure_core'),
							'hide' 		=> esc_html__('None, hide it','doccure_core'),
						)
					)
			);

			$form_el->form_process_select(
				array('name' 	=> esc_html__('Breadcrumb?','doccure_core'),
					'id' 		=> 'breadcrumbs',
					'std' 		=> 'enable',
					'wrapper_start' => 'yes',
					'wrapper_end' 	=> 'no',
					'wrapper_class' => 'custom-wrapper',
					'classes' 	=> '',
					'desc' 		=> esc_html__('Enable or disable breadcrumb for this page.','doccure_core'),
					'options' 	=> array(
						'enable' 	=> esc_html__('Enable','doccure_core'),
						'disable'	=> esc_html__('Disable','doccure_core'),
					)
				)
			);

			$form_el->form_process_color(
				array('name' 	=> esc_html__('Breadcrumb background color','doccure_core'),
					'id' 		=> 'title_bar_bg',
					'std' 		=> '',
					'classes' 	=> '',
					'desc' 		=> esc_html__('Enable or disable breadcrumb for this page.','doccure_core'),
				)
			);

			$form_el->form_process_color(
				array('name' 	=> esc_html__('Breadcrumb text color','doccure_core'),
					'id' 		=> 'title_bar_text',
					'std' 		=> '',
					'wrapper_start' => 'no',
					'wrapper_end' 	=> 'yes',
					'wrapper_class' => '',
					'classes' 	=> '',
					'desc' 		=> esc_html__('Enable or disable text for this page.','doccure_core'),
				)
			);

			$form_el->form_process_select(
					array('name' 	=> esc_html__('Choose Resolution Slider','doccure_core'),
						'id' 		=> 'rev_slider',
						'classes' 	=> 'rev',
						'std' 		=> '',
						'desc' 		=> esc_html__('Select Revolution Slider.','doccure_core'),
						'options' 	=> $revsliders
					)
			);

			$form_el->form_process_textarea(
					array('name' 	=> esc_html__('Add shortcode','doccure_core'),
						'id' 		=> 'shortcode',
						'classes' 	=> 'shortcode',
						'std' 		=> '',
						'desc' 		=> esc_html__('Add any shortcode in textarea','doccure_core'),
					)
			);
		?>
		</div>
		
		<div id="am_header_tab" style="display:none" > 
		<?php
			 
 
			$form_el->form_process_select(
				array('name' 	=> esc_html__('Header Style','doccure_core'),
					'id' 		=> 'header_style',
					'std' 		=> 'show',
					'desc' 		=> '',
					'options' => array(
						'default' 	=> esc_html__('Default','doccure_core'),
						'header_transparent'	=> esc_html__('Transparent','doccure_core'),
						'header_fixed'	=> esc_html__('Fixed','doccure_core'),
					)
				)
		);


			 
		?>
		</div>
		
		

		 
		
	</div>
</div>
