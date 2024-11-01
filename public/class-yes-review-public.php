<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://yesreview.com
 * @since      1.0.0
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/public
 */

/**
 * Public setup
 *
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/public
 * @author     Tim Switzer <yesmail@tlslogic.com>
 */
class Yes_Review_Public {

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
	 * API Endpoint
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $endpoint    URL to API 
	 */
	private $endpoint = 'https://yesreview.com/api/office/reviews';
	
	
	/**
	 * Try Cache First
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $from_cache    Pull from transient or not
	 */
	private $from_cache = true;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $yes_review       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $yes_review, $version ) {

		$this->yes_review = $yes_review;
		$this->version = $version;
		
		$this->add_shortcode();

	}

	public function add_shortcode(){
	    add_shortcode( 'yesreviews', array( &$this, 'yesreview_shortcode' ) );
	}
	
	
	function yesreview_shortcode( $atts, $content = null)	{
	
	    $default_params = array(
				'profiles' => 'google, yelp, facebook',
				'limit' => '25',
				'minrating' => 4,
			    'locations' => 'all'
			);
	    
	    $atts = shortcode_atts( 
			$default_params, 
			$atts
		);
	    
	    extract( $atts );
	    
	    
	    
	    
	    
	    $yesreview_options = get_option( 'yesreview_options', array() );
	    
	    if(empty($yesreview_options['yesreview_account_id']))
	        return $content;
	    
	    $params = $default_params;
	    
	    $plimit = (int)$limit;
	    if($plimit > 0 && $plimit <= 250)
	        $params['limit'] = $plimit;
	    
	    
	    $pminrating = (int)$minrating;
	    if($pminrating > 0 && $pminrating <= 5)
	        $params['minrating'] = $pminrating;
	    
	    $get_profiles = array_map('trim', explode(',', $profiles));
	    $pprofiles = array();
	    if(!empty($get_profiles)){
	        foreach($get_profiles as $profile){
	            
	            if(preg_match('/^(Google|Yelp|Facebook)$/i',$profile))
	                $pprofiles[] = strtolower($profile);
	                
	            
	        }
	        
	        if(!empty($pprofiles))
	            $params['profiles'] = implode('|',$pprofiles);
	    }
	    
	    
	    $get_locations = array_map('trim', explode(',', $locations));
	    $plocations = array();
	    if(!empty($get_locations)){
	        
	        foreach($get_locations as $location){
	            
	            if(is_numeric($location))
	                $plocations[] = (int)$location;
	            
	        }
	        
	        if(!empty($plocations))
	            $params['locations'] = implode('|',$plocations);
	        
	    }
	    
	   
	    $url = $this->endpoint;
	    $key = $yesreview_options['yesreview_account_id'];
	    
	    $cachekey = 'yr_c_'.$key;
	    if(!empty($params)){
	        
	        asort($params);
	        $cachekey .= md5(serialize($params));
	       
	    }
	        
	    
	    $cachekey = strlen($cachekey) > 40 ? substr($cachekey, 0, 40) : $cachekey;
	    
	    $jsondata = Yes_Review::_get_remote_html($url,$key,$params,$this->from_cache); 
	   
	    if(empty($jsondata))
	        return $content;
	    
	    $returndata = json_decode($jsondata,true);
	    
	    $profiles = '';
	   
	    if(!empty($returndata['data']['reviews'])){
	        
	        $returndata['data']['yesreview_optional'] = isset($yesreview_options['yesreview_optional']) ? $yesreview_options['yesreview_optional'] : 0;
	        
	        if ( !$this->from_cache || false === ( $profiles = get_transient( $cachekey ) ) ) {
	            
	            ob_start();
	            include_once plugin_dir_path( __FILE__ ) . 'partials/yes-review-public-display.php';
	            $profiles = ob_get_clean();
	            
	            set_transient( $cachekey, $profiles, 12 * HOUR_IN_SECONDS );
	        }
	        
	        
	        
	    }
	    
	    
	    // this will display our message before the content of the shortcode
	    return $profiles . ' ' . $content;
	
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	    wp_enqueue_style( $this->yes_review . '_fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->yes_review, plugin_dir_url( __FILE__ ) . 'css/yes-review-public.min.css', array(), $this->version, 'all' );

	}



}
