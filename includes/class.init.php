<?php

class init_bp_social_connect extends bpc_config{

	var $settings;

	public function __construct(){
		$this->settings = $this->get();
		new bp_social_connect_facebook;
		new bp_social_connect_twitter;
		new bp_social_connect_google;
		add_action('wp_head',array($this,'ajaxurl'));
		add_action('login_footer',array($this,'ajaxurl'));
		add_action('bp_before_member_body',array($this,'verify_email'));
		add_action('login_footer',array($this,'display_social_login'));
		add_action('bp_after_sidebar_login_form',array($this,'display_social_login'));
	}	

	function display_social_login(){
		echo '<div class="bp_social_connect">';
		do_action('bp_social_connect');
		echo '</div>';
	}

	function verify_email(){
		if (!is_user_logged_in()) return;
		global $current_user;
		get_currentuserinfo();
		if (empty($current_user->user_email)) {
		    echo '<div class="message error"><p style="text-transform: none;">'.sprintf(__('Please update your email id in Profile - Settings , your password is %s','bp-social-connect'),strtolower($current_user->user_login).'@123').'</p></div>';
		}
	}
	function ajaxurl() {
		wp_nonce_field($this->settings['security'],$this->security_key);
	?>
		<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
		<style>
			<?php echo $this->settings['button_css']; ?>
		</style>
	<?php
	}
}

add_action('init','initialise_bp_socil_connect');
function initialise_bp_socil_connect(){
	new init_bp_social_connect;	
}

