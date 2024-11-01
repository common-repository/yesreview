<?php
/**
 * The admin-specific settings of the plugin.
 *
 * @link       https://yesreview.com
 * @since      1.0.0
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/admin
 */



/**
 * The admin-specific settings of the plugin.
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/admin
 * @author     Tim Switzer <yesmail@tlslogic.com>
 */
class YesReview_Settings {
    
    /**
     * API Endpoint
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $endpoint    URL to API
     */
    
    private $endpoint = 'https://yesreview.com/api/office/locations';
	
	
	public function __construct()
    {
        
        
        register_setting('yesreview_options', 'yesreview_options');
        
            add_settings_section('yesreview_admin_section', null, array(
                $this,
                'yesreview_settings_section'
            ), 'yesreview');
            
            
            add_settings_section('yesreview_optional_section', null, array(
            $this,
            'yesreview_thankyou_section'
                ), 'yesreview_optional');
            
            
            add_settings_field('yesreview_optional_thankyou', // ID
            __('Display \'Yes Review\' Link:', 'yesreview'), // Title
            array(
            $this,
            'yesreview_thankyou_cb'
                ), // Callback
                'yesreview_optional', // Page
                'yesreview_optional_section') // Section
                ;
            
            add_settings_field('yesreview_account_id', // ID
        __('YesReview Account API', 'yesreview'), array($this, 'input_text'), // Callback
        'yesreview', // Page
        'yesreview_admin_section', array('descr' => '',
                'name' => 'yesreview_options',
                'key' => 'yesreview_account_id'
            ));
            
            add_settings_section('yesreview_admin_shortcode_section', null, array(
                $this,
                'yesreview_shortcode_section'
            ), 'yesreview_shortcode');
            
            add_settings_field('yesreview_review_limit', // ID
        __('# of Reviews', 'yesreview'), // Title
        array(
                $this,
                'input_text'
            ), // Callback
        'yesreview_shortcode', // Page
        'yesreview_admin_shortcode_section', // Section
        array(
                'descr' => 'Number of Reviews to Display (default: 25)',
                'name' => 'yesreview_options',
                'key' => 'review_limit'
            ));
            
            add_settings_field('yesreview_review_minrating', // ID
        __('Minimum Rating  ', 'yesreview'), // Title
        array(
                $this,
                'input_text'
            ), // Callback
        'yesreview_shortcode', // Page
        'yesreview_admin_shortcode_section', // Section
        array(
                'descr' => 'Minimum Star Rating to Display (default: <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>)',
                'name' => 'yesreview_options',
                'key' => 'review_minrating'
            ));
            
            add_settings_field('yesreview_review_profiles', // ID
        __('Profiles', 'yesreview'), // Title
        array(
                $this,
                'yesreview_profile_locations'
            ), // Callback
        'yesreview_shortcode', // Page
        'yesreview_admin_shortcode_section') // Section
        ;
            
            add_settings_field('yesreview_review_locations', // ID
        __('Locations', 'yesreview'), // Title
        array(
                $this,
                'yesreview_office_locations'
            ), // Callback
        'yesreview_shortcode', // Page
        'yesreview_admin_shortcode_section') // Section
        ;
    }
	
	
	public function yesreview_profile_locations(){
	     
	    $profiles = array('Google', 'Facebook' , 'Yelp');
	    
	    foreach($profiles as $profile){
	        
	        $icon = strtolower($profile);
	        
	        $this->input_checkbox(array(
	            'descr' =>   $profile . ' (<i class="fa fa-'.$icon.'"></i>)',
	            'name'	=> 'yesreview_options',
	            'key'	=> 'review_location_'.$profile,
	            'value' => $profile,
	            'class' => 'profiles'
	        ), false);
	        
	        echo '<br />';
	    }
	    
	    
	    echo '<br><small>Social Profiles Reviews to Display (default: all)</small>';
	     
	}
	
	
	
	public function yesreview_thankyou_cb(){
	
	    $profiles = array('Google', 'Facebook' , 'Yelp');
	     
	    $this->input_checkbox(array(
	            'descr' =>   'Yes!',
	            'name'	=> 'yesreview_options',
	            'key'	=> 'yesreview_optional',
	            'value' => '1',
	            'class' => 'optional'
	        ), false);
	     
	     
	    
	
	}
	
	
	
	public function yesreview_office_locations(){
	    
	    $yesreview_options = get_option( 'yesreview_options', array() );
	    
	    if (empty( $yesreview_options['yesreview_account_id'] ) ){
	        echo 'Enter Account API to display available locations';
	        return true;
	    }
	        
        $url = $this->endpoint;
	    $key = $yesreview_options['yesreview_account_id'];
	    $params = array('locations' => 'all');
	    
	    $jsondata = Yes_Review::_get_remote_html($url,$key,$params, false);
	   
	    if(!empty($jsondata)){
	    
	        $returndata = json_decode($jsondata,true);
	    
	    
	    
	        if(!empty($returndata['data']['locations'])){
	    
	            $office_index = 1;
	            foreach ($returndata['data']['locations'] as $location){
	               
	                $this->input_checkbox(array(
	                    'descr' => $location['name'],
	                    'name'	=> 'yesreview_options',
	                    'key'	=> 'review_location_'.$location['office_uid'],
        	            'value' => $office_index,
        	            'class' => 'locations'
	                ));
	                echo '<br />';
	                $office_index++;
	            }
	        }
	    
	         
	    }
	    
	    
	    echo '<br><small>Select office location(s) you would like to display reviews (default: all)</small>';
	   
	}
	
	public function yesreview_shortcode_section(){
	    
	    _e( '<h2>Shortcode Generator </h2> <p>Enter desired options below to generate the Shortcode that you can use in a blog or post to display your reviews.</p>', 'yesreview' );
	    
	}
	
	public function yesreview_settings_section() {
	    _e( 'Please enter your API from your YesReview account. ', 'yesreview' );
	}
	
	public function yesreview_thankyou_section() {
	    //_e( 'Thank you for using YesReview.', 'yesreview' );
	}
	
	public function input_text($args) {
	    
	    $option = $this->extract_option_data($args); 
	    // Render the output
	    echo '<input type="text" id="'. $option['id'] .'" name="'. $option['name'] .'" value="'.stripslashes(esc_attr( $option['value'] )).'" class="regular-text shortcodeoptions" /> <span class="shortcodeoption_error" id="error_'.$option['id'].'"></span>';
	    echo ( isset($args['descr']) ? '<br><small><label for="'.$option['id'].'">'. $args['descr'] .'</label></small>' : '' );
	}
	
	
	/**
	 * Prints checkbox options
	 */
	public function input_checkbox($args, $escape = true) {
	    $option = $this->extract_option_data($args);
	    
	    $class = !empty($args['class']) ? $args['class'] : '';
	    $value = !empty($args['value']) ? $args['value'] : '';
	    // Render the output
	    echo '<label for="'. $option['id'] .'"><input type="checkbox" id="'. $option['id'] .'" name="'. $option['name'] .'" size="20" value="'.$value.'" '.checked($option['value'],$value,false).'  class="shortcodeoptions '.$class.'" />';
	    
	    echo $escape ? htmlspecialchars($args['descr']) : $args['descr'];
	    echo '</label>';
	}
	
	
	/**
	 * Prepares an $option array with name, id and value for this option
	 */
	public function extract_option_data($args) {
	    $option = array();
	    $yesreview_options = get_option( 'yesreview_options', array() );
	    if ( !empty($args['key']) ) {
	        $option['name'] = $args['name'] . '[' . $args['key'] . ']';
	        $option['id'] = $args['name'].'-'.$args['key'];
	        $settings = get_option($args['name'],'');
	        $option['value'] = ( isset($yesreview_options[$args['key']]) ? $yesreview_options[$args['key']] : '');
	    } else {
	        $option['name'] = $args['name'];
	        $option['id'] = $args['name'];
	        $option['value'] = ( isset( $yesreview_options[$args['name']] ) ? $yesreview_options[$args['name']] : '');
	    }
	    return $option;
	}
	
	

}

/**
 * Initialize the Settings class
 */
$yesreview_settings = new YesReview_Settings();