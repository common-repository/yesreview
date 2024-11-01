<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://yesreview.com
 * @since      1.0.0
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/includes
 */

/**
 * The core plugin class.
 *
 *
 * @since      1.0.0
 * @package    Yes_Review
 * @subpackage Yes_Review/includes
 * @author     Tim Switzer <yesmail@tlslogic.com>
 */
class Yes_Review {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Yes_Review_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $yes_review    The string used to uniquely identify this plugin.
	 */
	protected $yes_review;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'YES_REVIEW_VERSION' ) ) {
			$this->version = YES_REVIEW_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->yes_review = 'yes-review';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Yes_Review_Loader. Orchestrates the hooks of the plugin.
	 * - Yes_Review_i18n. Defines internationalization functionality.
	 * - Yes_Review_Admin. Defines all hooks for the admin area.
	 * - Yes_Review_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-yes-review-loader.php';

		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-yes-review-admin.php';
		
		//$admin = new Yes_Review_Admin($this->yes_review,$this->version);

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-yes-review-public.php';

		$this->loader = new Yes_Review_Loader();

	}

	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

	    $plugin_name = $this->get_yes_review();
	    
		$plugin_admin = new Yes_Review_Admin( $plugin_name, $this->get_version() );
    
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_clear_cache', $plugin_admin, 'clear_cache' );
		
		
		
		$this->loader->add_action( 'plugin_action_links_'. plugin_basename(plugin_dir_path( __DIR__ ) .$plugin_name.'.php' ), $plugin_admin, 'action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Yes_Review_Public( $this->get_yes_review(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_yes_review() {
		return $this->yes_review;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Yes_Review_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	

	
	

	
	
	public static function displaystars($rating = 1){
	
	
	    $stars = '';
	    for ($x = 1; $x <= $rating; $x++) {
	        $stars .= '<i class="fa fa-star" ></i>';
	
	    }
	
	    return $stars;
	
	}
	
	
	public static function _get_remote_html($url, $key, $params = array(), $cache = true ) {
	     
	    if ( empty( $url ) || empty( $key )) return;
	    
	  
	  
	    $cachekey = $key;
	    if(preg_match('/reviews$/',$url))
	        $cachekey = 'rv_' . $cachekey;
	    
	    
	    if(!empty($params)){
	       
	       asort($params);
	       $cachekey .= md5(serialize($params));
	       $url .= '?' . http_build_query($params) . "\n";
	    }
	    
	    
	    
	    $cachekey = 'yr_' . $cachekey;
	    $cachekey = strlen($cachekey) > 40 ? substr($cachekey, 0, 40) : $cachekey;
	  
	    $html = '';
	    // Check for transient, if none, grab remote HTML file
	    if ( !$cache ||  false === ( $html = get_transient( $cachekey ) ) ) { 
	     
	    // Get remote HTML file
	    $response = wp_remote_get( $url,
	        array( 'timeout' => 10,
	            'headers' => array( 'X-MW-PUBLIC-KEY' => $key,
	                'X-MW-TIMESTAMP'=> time() )
	        ) ); 
	     
	    // Check for error
	    if ( is_wp_error( $response ) ) {
	        return;
	    }
	     
	    // Parse remote HTML file
	    $data = wp_remote_retrieve_body( $response );
	     
	    // Check for error
	    if ( is_wp_error( $data ) ) {
	        return;
	    }
	     
	    $html = $data;
	     

	    set_transient( $cachekey, $data, 12 * HOUR_IN_SECONDS );
	     
	    }
	     
	    return $html;
	     
	}
	
	

}
