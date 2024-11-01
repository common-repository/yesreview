<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://yesreview.com
 * @since      1.0.0
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/admin
 * @author     Tim Switzer <yesmail@tlslogic.com>
 */
class Yes_Review_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $yes_review    The ID of this plugin.
	 */
	private $yes_review;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $yes_review       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $yes_review, $version ) {
	    
	   
		$this->yes_review = $yes_review;
		$this->version = $version;
		
		// Creating the admin menu
		add_action( 'admin_menu', array( $this, 'yesreview_menu' ) );
		
		// Register the Settings fields
		add_action( 'admin_init', array( $this, 'yesreview_settings_init' ) );
		
		
		add_action( 'wp_ajax_save_optional_option', array( &$this, 'ajax_save_optional_option' ) );
		

	}

	
	
	
	/**
	 * Settings class initialization
	 */
	public function yesreview_settings_init() {
	    
	   
	    include_once 'class-yes-review-settings.php';

	    
	}

    function ajax_save_optional_option(){
        
        if (! wp_verify_nonce($_REQUEST['nonce'], 'ajax_save_optional_option_nonce') || !isset($_POST['optional_option']))
            die('-1');
        
        $data = array();
        
        $data['yesreview_optional'] = $_POST['optional_option'] == 'true' ? 1 : 0;
        
        $options = get_option('yesreview_options');
        
        if (! is_array($options))
            $options = array();
        
        $update_options = array_merge($options, $data);
        
        if (update_option('yesreview_options', $update_options)) {
            die('1');
        } else {
            die('0');
        }
        
      
    }

	
	
	

	
	
	public function yesreview_menu() {
	    add_options_page( __('YesReview', 'yesreview' ),
	    __( 'YesReview Setup', 'yesreview' ),
	    'manage_options', 'yesreview', array( $this, 'yesreview_menu_cb' ) );
	}
	

	
	
	public function yesreview_menu_cb() {
	    
	    $yesreview_options = get_option( 'yesreview_options', array() );
	   
	 
	    ?>
<div class="wrap">
	<h1><?php _e( 'YesReview Settings', 'yesreview' ); ?></h1>



	<div>
		<form action="options.php" method='POST'>

			<div id="post-body-api" class="pull-left">
				<div id="postbox-api-container-1">
		<?php
			settings_fields( 'yesreview_options' );
			do_settings_sections( 'yesreview' );
			
			submit_button();
			
			?>
			</div>
			</div>


			<div id="postbox-welcome-note" class="postbox pull-right">

				<div class="inside">
					<div class="alignright browser-icon">
						<a href="https://yesreview.com" target="_blank"><img
							src="<?php echo plugin_dir_url( __FILE__ ) . 'images/yes-review-logo.png'?>"
							alt="YesReview"></a>
					</div>


					<h3>Thank you for using YesReview.</h3>
					<p>
						If you would like more information or to sign up for an account
						please visit us at <a href="https://yesreview.com" target="_blank">yesreview.com</a>
					</p>
					<div class="alignleft " style="width: 350px;"><?php do_settings_sections( 'yesreview_optional' );?>
					
					<div style="margin-top:-15px"><small>(<i>Optional but we would appreciate it!</i>)</small></div>
					
					<?php echo '<span id="ajax_save_optional_option_nonce" class="hidden">' . wp_create_nonce( 'ajax_save_optional_option_nonce' ) . '</span>'; ?>

</div>

					<div
						style="clear: both; width: 500px; border-top: 1px solid #eee; padding: 7px;">
						<p>
							<small>Note: We utilize caching to improve performance but if you
								are having issues with recent updates you can try <input
								name="clearcache" id="clearcache" class="button button-link"
								value="clearing the cache" type="button"> to make a new request
								to YesReview's servers. <span style="color: green;"
								id="cache_status"></span>
							</small>
						</p>
					</div>
					<div class="clear"></div>

				</div>
			</div>

			<div id="post-body-shortcode" class="pull-left">
				<div id="postbox-shortcode-container-1">
			<?php 
			do_settings_sections( 'yesreview_shortcode' );
			?>
				<div class="postbox  postbox-container shortcode-copy-box">
						<div class="inside">
							<h5 style="">
								<span>Copy and paste the shortcode below into any page or post
									to display your reviews.</span>
							</h5>
							<div class="shortcodeoutput"></div>
						</div>
					</div>

<?php 
			submit_button();
			
			
		?>
			</div>









			</div>
		</form>
	</div>

</div>
<?php
	    }
	    

	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		
	    wp_enqueue_style( $this->yes_review . '_fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );
	    
		wp_enqueue_style( $this->yes_review, plugin_dir_url( __FILE__ ) . 'css/yes-review-admin.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		
		wp_enqueue_script( $this->yes_review, plugin_dir_url( __FILE__ ) . 'js/yes-review-admin.min.js', array( 'jquery' ), $this->version, false );

	}
	
	public function action_links($links){
	    
	    $settings_link = '<a href="options-general.php?page=yesreview">' . __( 'Settings' ) . '</a>';
	    $links = array_merge(array($settings_link), $links );
	    return $links;
	    
	}

	public function clear_cache(){
	    
	    $this->delete_yesreview_cache();
	    
	    echo 'cache detelted';
	    
	    wp_die();
	}
	
	private function  delete_yesreview_cache() {
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%\_transient\_yr\_%'" );
    }


	
}
