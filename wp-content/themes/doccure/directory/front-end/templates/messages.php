<?php
/**
 *
 * The template part for displaying the dashboard Help and Support
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $post,$current_user,$doccure_options;
 $post_id 		= $post->ID; 

$user_id		= doccure_get_linked_profile_id( $post_id,'post' );
$profile_name	= doccure_full_name( $user_id );
$doctor_avatar 	= apply_filters(
					'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 255, 'height' => 250 ), $post_id ), array( 'width' => 255, 'height' => 250 )
				);

$active_profile_id		= doccure_get_linked_profile_id( $current_user->ID );
$wp_user_id				= !empty( $current_user->ID ) ? $current_user->ID : 0;
$active_user_avatar 	= apply_filters(
							'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 255, 'height' => 250 ), $active_profile_id ), array( 'width' => 255, 'height' => 250 )
						);

$active_name	= doccure_full_name( $active_profile_id );
$active_name	= !empty( $active_name ) ? $active_name : '';
$name			= doccure_full_name( $post_id );
$name			= !empty( $name ) ? $name : '';

if( ( !empty( $doccure_options['chat'] ) && ( $doccure_options['chat'] === 'chat' || $doccure_options['chat'] === 'inbox' ) ) ){
?>

<!-- <div class="dc-chatpopup">
	<div class="dc-chatbox">
		<div class="dc-messages dc-verticalscrollbar dc-dashboardscrollbar load-dc-chat-message">
			<?php do_action('fetch_single_users_threads',$user_id,$current_user->ID); ?>
		</div>
		<div class="dc-replaybox">
			<div class="form-group">
				<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_attr_e('Type something','doccure');?>"></textarea>
			</div>
			<div class="dc-iconbox">
				<a href="javascript:;" class="dc-btnsendmsg dc-send-single" data-msgtype="normals" data-receiver_id="<?php echo intval( $user_id );?>" data-status="unread">
					<i class="fab fa-telegram-plane"></i><?php //esc_html_e('Send','doccure');?>
				</a>
			</div>
		</div>
	</div>
	<?php if( !empty( $doctor_avatar ) ){ ?>
		<div id="dc-getsupport" class="dc-themeimgborder" data-currentid="<?php echo esc_attr( $wp_user_id );?>">
			<img src="<?php echo esc_url( $doctor_avatar );?>" alt="<?php echo esc_attr( $profile_name );?>">
		</div>
	<?php } ?>
</div> 
<script type="text/template" id="tmpl-load-chat-replybox">
<div class="dc-memessage dc-readmessage">
	<figure><img src="{{data.img_url}}" alt="{{data.name}}"></figure>
	<div class="dc-description">
		<p>{{data.message}}</p>
		<div class="clearfix"></div>
		<time datetime="2017-08-08">{{data._date}}</time>
	</div>
</div>
</script>
<script type="text/template" id="tmpl-load-chat-messagebox">
<# if( !_.isEmpty(data.chat_nodes) ) { #>
<# 
_.each( data.chat_nodes , function( element, index ) { 
	var chat_class = 'dc-offerermessage dc-msg-thread';
	if(element.chat_is_sender === 'yes'){
		chat_class = 'dc-memessage dc-readmessage dc-msg-thread';
	}
	
	load_message	= element.chat_message;
#>
<div class="dc-offerermessage {{chat_class}}" data-id="{{element.chat_id}}">
	<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
	<div class="dc-description">
		<p>{{load_message}}</p>
		<div class="clearfix"></div>
		<time datetime="2017-08-08">{{element.chat_date}}</time>
		<div class="clearfix"></div>
	</div>
</div>
<# }); #>
<# } #>
</script>-->
<?php
	// $inline_script_v = 'jQuery(document).on("ready", function() { 
	// 	eonearea = jQuery(".reply_msg").emojioneArea();
	// 	eonearea[0].emojioneArea.setText("");
	// 	refreshScrollBarObject();
	// });';
	// wp_add_inline_script( 'doccure-callback', $inline_script_v, 'after' );
															 
}
