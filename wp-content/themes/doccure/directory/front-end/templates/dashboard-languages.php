<?php 
/**
 *
 * The template part for displaying the user profile avatar
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id		= $linked_profile;
$languages 		= doccure_get_taxonomy_array('languages');
?>
<div class="dc-tabsinfo">
	 
	<div class="dc-settingscontent dc-sidepadding">
		<div class="dc-formtheme dc-userform">
			<div class="form-group">
			<label><?php esc_html_e('Select Languages', 'doccure'); ?></label>
				<select data-placeholder="<?php esc_attr_e('Languages', 'doccure'); ?>" name="settings[languages][]" multiple class="chosen-select">
					<?php if( !empty( $languages ) ){
						foreach( $languages as $key => $item ){
							$selected = '';
							if( has_term( $item->term_id, 'languages', $post_id )  ){
								$selected = 'selected';
							}
						?>
						<option <?php echo esc_attr($selected);?> value="<?php echo intval( $item->term_id );?>"><?php echo esc_html( $item->name );?></option>
					<?php }}?>
				</select>
			</div>
		</div>
	</div>
</div>