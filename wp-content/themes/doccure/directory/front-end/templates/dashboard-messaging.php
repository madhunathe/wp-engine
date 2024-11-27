<?php 
/**
 * Messaging template
 **/
global $current_user,$doccure_options;
$user_identity = $current_user->ID;
?>
<section class="dc-haslayout am-chat-module">
	<div class="">
	    <div class="dc-dashboardbox dc-messages-holder">
	        <div class="dc-dashboardboxtitle">
	           <h2><?php esc_html_e('Messages', 'doccure'); ?></h2>
			</div>
			<?php 
			if (isset($_GET['ref']) && $_GET['ref'] == 'chat' && $_GET['identity'] == $user_identity) {
				if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] === 'guppy' ){
					echo do_shortcode('[getGuppyConversation]');
				}else{?>
				<div class="dc-dashboardboxtitle dc-titlemessages chat-current-user"></div>
				<div class="dc-dashboardboxcontent dc-dashboardholder dc-offersmessages"><?php do_action('fetch_users_threads', $user_identity);?></div>
			<?php }}?>
		</div>
	</div>
</section>
<?php if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] !== 'guppy' ){?>
<script type="text/template" id="tmpl-load-chat-replybox">
<div class="dc-messages dc-verticalscrollbar dc-dashboardscrollbar"></div>
<div class="dc-replaybox">
	<div class="form-group">
		<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_attr_e('Type message here', 'doccure'); ?>"></textarea>
	</div>
	<div class="dc-iconbox">
		<div id="container"></div>
		<a href="javascript:;" class="dc-btnsendmsg dc-send" data-status="unread" data-receiver_id="{{data.receiver_id}}"><i class="fab fa-telegram-plane"></i><?php //esc_html_e('Send', 'doccure'); ?></a>
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
<div class="{{chat_class}}" data-id="{{element.chat_id}}">
	<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
	<div class="dc-description">
		<p>{{load_message}}</p>
		<div class="clearfix"></div>
		<time datetime="2017-08-08">{{element.chat_date}}</time>
		<div class="clearfix"></div>
		<# if(element.chat_is_sender === 'yes'){ #>
		
		<# } #>
	</div>
</div>
<# }); #>
<# } #>
</script>
<script type="text/template" id="tmpl-load-chat-recentmsg-data">
	{{data.desc}}
</script>
<script type="text/template" id="tmpl-load-user-details">
<a href="javascript:;" class="dc-back back-chat"><i class="ti-arrow-left"></i></a>
<div class="dc-userlogedin">
	<figure class="dc-userimg">
		<img src="{{data.chat_img}}" alt="{{data.chat_name}}">
	</figure>
	<div class="dc-username">
		<h3>{{data.chat_name}}</h3>
	</div>
</div>
</script>
<?php }?>