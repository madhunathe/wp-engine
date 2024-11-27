<?php 
require doccureGlobalSettings::get_plugin_path() . 'libraries/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Import Users
 * @return{}
 */     

if ( !class_exists('doccure_Import_User') ) {    
    class doccure_Import_User {        
        function __construct(){
            // Constructor Code here..
   		}
		
		/*
		 * @import users
		 */
		public function doccure_import_user(){
		
			global $wpdb, $wpdb_data_table;
	
			// User data fields list used to differentiate with user meta
			$userdata_fields       = array(
				'ID', 
				'username', 
				'user_pass',
				'user_email', 
				'user_url', 
				'user_nicename',
				'display_name', 
				'user_registered', 
				'first_name',
				'last_name', 
				'nickname', 
				'description',
				'rich_editing', 
				'comment_shortcuts', 
				'admin_color',
				'use_ssl', 
				'show_admin_bar_front', 
				'show_admin_bar_admin',
				'role'
			);
			
			$memberships			= array(
				'Manchester Academy of Oral Medicine and Radiology',
				'United State Dental Council',
				'International Association of General Dentistry (IAGD)',
				'International Federation of Dental Educators and Associations (IFDEA, USA)',
				'Sydney Academy of Aesthetic & Cosmetic Dentistry',
				'Behavioral Health Charge Nurse',
				'Cardiac Catheterization Lab Nurse',
				'Speech-Language Pathologist',
				'Occupational Therapy Assistant'
			);
			
			$weeklyDays	= doccure_get_week_array();
			$weeklyDays	= array_keys($weeklyDays);
			
			$wp_user_table		= $wpdb->prefix.'users';
			$wp_usermeta_table	= $wpdb->prefix.'usermeta';

			if ( isset( $_FILES['users_csv']['tmp_name'] ) ) {
				$file = $_FILES['users_csv']['tmp_name'];
				$name = !empty( $_FILES['users_csv']['name'] ) ? $_FILES['users_csv']['name'] : '';
				
				$filetype	= '';
				if( !empty( $name ) ){
					$filetype = pathinfo($name, PATHINFO_EXTENSION);
				}
				
				$import_type	= 'upload';
				
			} else{
				$file 			= doccureGlobalSettings::get_plugin_path().'/import-users/users.xlsx';
				$filetype		= 'xlsx';
				$import_type	= 'dummy';
			}
			
			try {
				//Load the excel(.xls/.xlsx) file
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
			}

			$worksheet = $spreadsheet->getActiveSheet();
			// Get the highest row and column numbers referenced in the worksheet
			$total_rows = $worksheet->getHighestRow(); // e.g. 10
			$highest_column = $worksheet->getHighestColumn(); // e.g 'F'
	
			$first 	= true;
			$rkey 	= 0;
			
			for($row =1; $row <= $total_rows; $row++) {
	
				// If the first line is empty, abort
				// If another line is empty, just skip it
				if ( empty( $row ) ) {
					if ( $first )
						break;
					else
						continue;
				}
	
				// If we are on the first line, the columns are the headers
				if ( $first ) {
					$line = $spreadsheet->getActiveSheet()
									->rangeToArray(
										'A' . $row . ':' . $highest_column . $row,     // The worksheet range that we want to retrieve
										NULL,        // Value that should be returned for empty cells
										TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
										FALSE,       // Should values be formatted (the equivalent of getFormattedValue() for each cell)
										FALSE        // Should the array be indexed by cell row and cell column
									);
					//$line = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, NULL, TRUE, FALSE);
					$headers 	= !empty( $line[0] ) ? $line[0] : array();
					$first 		= false;
					continue;
				} else{
					//$data = array_map("utf8_encode", $line); //Encoding other than english language
					$data = $spreadsheet->getActiveSheet()
									->rangeToArray(
										'A' . $row . ':' . $highest_column . $row,     // The worksheet range that we want to retrieve
										NULL,        // Value that should be returned for empty cells
										TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
										FALSE,       // Should values be formatted (the equivalent of getFormattedValue() for each cell)
										FALSE        // Should the array be indexed by cell row and cell column
									);
				}

				// Separate user data from meta
				$userdata = $usermeta = array();
				foreach ( $data[0] as $ckey => $column ) {
					$column_name = trim( $headers[$ckey] );
					$column = trim( $column );

					if ( in_array( $column_name, $userdata_fields ) ) {
						$userdata[$column_name] = $column;
					} else {
						$usermeta[$column_name] = $column;
					}
				}

				// If no user data, bailout!
				if ( empty( $userdata ) )
					continue;

	
				$user = $user_id = false;
				
				if ( ! $user ) {
					if ( isset( $userdata['username'] ) )
						$user = get_user_by( 'login', $userdata['username'] );
	
					if ( ! $user && isset( $userdata['user_email'] ) )
						$user = get_user_by( 'email', $userdata['user_email'] );
				}
				
				$update = false;
				if ( $user ) {
					$userdata['ID'] = $user->ID;
					$update = true;
				}
	
				// If creating a new user and no password was set, let auto-generate one!
				if ( ! $update && $update == false  && empty( $userdata['user_pass'] ) ) {
					$userdata['user_pass'] = wp_generate_password( 12, false );
				}
				
				$am_data	= array();
				if (isset($update)&& $update == true) {
					$user_id = wp_update_user( $userdata );
					
					$new_user 	= new WP_User( $user_id );
					
					if( $userdata['role'] === 'doctors' ){
						$new_user->set_role( 'doctors' );
						$role	= 'doctors';
					} else if( $userdata['role'] === 'hospitals' ){
						$new_user->set_role( 'hospitals' );
						$role	= 'hospitals';
					} else{
						$new_user->set_role( 'regular_users' );	
						$role	= 'regular_users';
					}
					
					$display_name	= $userdata['first_name'].' '.$userdata['last_name'];
					$display_name   = !empty( $userdata['display_name'] ) ? $userdata['display_name'] : $display_name;
					
				} else {
					$display_name	= $userdata['first_name'].' '.$userdata['last_name'];

					$db_username 	= !empty( $userdata['username'] ) ? $userdata['username'] : rand(1,99999);
					$db_user_pass 	= !empty( $userdata['user_pass'] ) ? $userdata['user_pass'] : '123456';
					$db_user_email  = !empty( $userdata['user_email'] ) ? $userdata['user_email'] : rand(1,9999).'@gmail.com';
					$db_user_url 	= !empty( $userdata['user_url'] ) ? $userdata['user_url'] : '';
					$db_nicename 	= !empty( $userdata['user_nicename'] ) ? sanitize_title( $userdata['user_nicename'] ) : $db_username;
					$display_name   = !empty( $userdata['display_name'] ) ? $userdata['display_name'] : $display_name;
					
					$sql = "INSERT INTO $wp_user_table (user_login, 
														user_pass, 
														user_email, 
														user_registered,
														user_status, 
														display_name, 
														user_nicename, 
														user_url
														) VALUES ('".$db_username."',
														'".md5($db_user_pass)."',
														'".$db_user_email."',
														'".date('Y-m-d H:i:s')."',
														0,
														'".$display_name."',
														'".$db_nicename."',
														'".$db_user_url."'
													)";
					$wpdb->query($sql);
					$lastid 	= $wpdb->insert_id;
					$new_user 	= new WP_User( $lastid );

					if( $userdata['role'] === 'doctors' ){
						$new_user->set_role( 'doctors' );
						$role	= 'doctors';
						
					} else if( $userdata['role'] === 'hospitals' ){
						$new_user->set_role( 'hospitals' );
						$role	= 'hospitals';
					} else if( $userdata['role'] === 'seller' ){
						$new_user->set_role( 'seller' );
						$role	= 'seller';
						$vendor_details	= array();
						$vendor_details['store_name']	= $display_name;

						update_user_meta( $new_user->ID, 'dokan_profile_settings', $vendor_details );
						update_user_meta( $new_user->ID, 'dokan_enable_selling', 'yes' );
						
					} else{
						$new_user->set_role( 'regular_users' );	
						$role	= 'regular_users';
					}

					$user_id = $new_user->ID;

				}
				
				// Include again meta fields
				$usermeta['user_id']	    = !empty( $user_id ) ? $user_id : 0;
				$usermeta['first_name']	    = !empty( $userdata['first_name'] ) ? $userdata['first_name'] : '';
				$usermeta['last_name']	    = !empty( $userdata['last_name'] ) ? $userdata['last_name'] : '';

				update_user_meta( $user_id, 'usertype', $userdata['role'] ); //update user type
				update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
				update_user_meta( $user_id, 'full_name', $display_name );
				update_user_meta( $user_id, 'rich_editing', 'true' );
				update_user_meta( $user_id, '_is_verified', $usermeta['is_verified'] );
				update_user_meta( $user_id, 'nickname', $display_name );
				
				//Update trial package 
				if( $userdata['role'] === 'doctors' ){
					
					if( function_exists('doccure_get_package_type') ){
						$trail_doctors_id	= doccure_get_package_type( 'package_type','trail_doctors');
						if( !empty( $trail_doctors_id ) ){
							doccure_update_package_data( $trail_doctors_id ,$user_id,'',1 );
						}
					}
				}
				
				// Is there an error o_O?
				if ( is_wp_error( $user_id ) ) {
					$errors[$rkey] = $user_id;
				} else {
					// If no error, let's update the user meta too!
					$db_schedules	= array();
					if ( $usermeta ) {
						if( $import_type === 'upload' ){
							$dc_options	= array();
							$content	= '';
							$post_excerpt = '';
							$doc_item_list = array('profile_image_id');
							
							foreach ( $usermeta as $metakey => $metavalue ) {
								$metavalue = maybe_unserialize( $metavalue );
								
								if( $metakey === 'content' ){
									$content = $metavalue;
								} else if( $metakey === 'short_description' ){
									$post_excerpt 							= $metavalue;
								} else if( in_array( $metakey, $doc_item_list ) ){
									$dc_options[$metakey] = $metavalue;
								} else{
									update_user_meta( $user_id, $metakey, trim( $metavalue ) );  
								}
								
							}

							$full_name    = $display_name;
							
							$post_id	= '';
							if (isset($update)&& $update == false) {
								//Create Post
								$user_post = array(
									'post_title'    => wp_strip_all_tags( $full_name ),
									'post_status'   => 'publish',
									'post_content'  => $content,
									'post_author'   => $user_id,
									'post_type'     => $role,
									'post_excerpt'	=> $post_excerpt
								);

								$post_id    = wp_insert_post( $user_post );
							}else{
								$linked_post	= get_user_meta( $user_id, '_linked_profile', true );
								$arg = array(
									'ID' 			=> $linked_post,
									'post_title'    => wp_strip_all_tags( $full_name ),
									'post_status'	=> 'publish',
									'post_author' 	=> $user_id,
								);
								$post_id    = wp_update_post( $arg );
							}
							
							if( !is_wp_error( $post_id ) ) {
								$number			= rand(9999, 999999);
								$string			= substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 2);

								$name_base	= 'dr';
								$am_fields_common	= array(
										'am_name_base' 		=> $name_base,
										'am_sub_heading'	=> $usermeta['tag_line'],
										'am_first_name'		=> $usermeta['first_name'],
										'am_last_name'		=> $usermeta['last_name'],
										'am_short_description' 		=> $usermeta['short_description'],
										'am_registration_number' 	=> $usermeta['registration_number'],
										'am_is_verified'			=> $usermeta['is_verified'],
									);
								update_user_meta( $user_id, '_linked_profile', $post_id );
								
								update_post_meta($post_id,'_address',$usermeta['address']);
								update_post_meta($post_id,'_latitude',$usermeta['latitude']);
								update_post_meta($post_id,'_longitude',$usermeta['longitude']);
								update_post_meta($post_id, '_is_verified', $usermeta['is_verified']);
								update_post_meta($post_id, '_profile_blocked', 'off');
								update_post_meta($post_id,'is_featured',0);
								
								if( $role == 'doctors' ){
									$user_type 									= 'doctors';
									$am_fields_common['am_gender']				= !empty( $usermeta['gender'] ) ? $usermeta['gender'] : '';
									
									update_post_meta($post_id,'am_doctors_data',$am_fields_common);
									
									update_post_meta($post_id, 'review_data', array());
									update_post_meta($post_id, 'rating_filter', 0);
									update_post_meta($post_id, 'am_gender', '');
									
									//update trial package
									if( function_exists('doccure_get_package_type') ){
										$trail_doctors_id	= doccure_get_package_type( 'package_type','trail_doctors');
										if( !empty( $trail_doctors_id ) ){
											doccure_update_package_data( $trail_doctors_id ,$user_id,'',1 );
										}
									}
									
									
								} elseif( $role == 'hospitals' ){

									$user_type 	= 'hospitals';
									$am_fields_common['am_availability']		= 'yes';

									update_post_meta($post_id,'am_hospitals_data',$am_fields_common);
								}
								
								      		           		
								update_post_meta($post_id, '_featured_timestamp', 0);
								
								//update profile picture
								if( !empty( $usermeta['profile_image_id'] ) ){
									set_post_thumbnail($post_id, $usermeta['profile_image_id']);
								}
								
								if( !empty( $usermeta['country'] ) ){
									$country = get_term_by( 'slug', $usermeta['country'], 'locations' );
									if( !empty( $country->term_id )) {
										wp_set_post_terms($post_id, $country->term_id, 'locations');	
									}
								}
								
								if( !empty( $usermeta['languages'] ) ){
									$lang_array	= explode(',',$usermeta['languages']);
									$lang		= array();
									foreach( $lang_array as $key => $item ){
										$langs = get_term_by( 'slug', $item, 'languages' );
										if( !empty( $langs ) ){
											$lang[] = $langs->term_id;
										}
									}

									if( !empty( $lang ) ){
										wp_set_post_terms($post_id, $lang, 'languages');
									}
								}

								//update privacy settings
								$settings		 = doccure_get_account_settings($user_type);
								if( !empty( $settings ) ){
									foreach( $settings as $key => $value ){
										$val = $key === '_profile_blocked' ? 'off' : 'on';
										update_post_meta($post_id, $key, $val);
									}
								}

								update_post_meta($post_id, '_linked_profile', $user_id);
								
								do_action('doccure_import_extra_fields',$user_id,$usermeta);
							}
						} else{
							foreach ( $usermeta as $metakey => $metavalue ) {
								$metavalue = maybe_unserialize( $metavalue );
								if( $metakey === '_linked_profile' ){
									$user_post_id	=  intval( trim( $metavalue ) );
									update_user_meta( $user_id, $metakey, intval( $user_post_id ) );
									update_post_meta(intval( $user_post_id ), '_linked_profile', $user_id);
									update_post_meta($user_post_id,'is_featured',0);
									update_post_meta($user_post_id, 'am_gender', '');
									
									update_user_meta( $user_id, '_is_verified', 'yes' ); 
									update_post_meta( $user_post_id, '_is_verified', 'yes');
									
									//update post author as well
									$arg = array(
										'ID' 			 => intval( $user_post_id ),
										'post_author'	 => intval($user_id),
									);
									wp_update_post( $arg );
								}else{
									update_user_meta( $user_id, $metakey, trim( $metavalue ) ); 
								}
							}
						} 
					}
					// If we created a new user, maybe set password nag and send new user notification?
					if ( ! $update ) {
					}
				}
	
				$rkey++;
			}
		}
	}
}