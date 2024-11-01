<?php

/**
 * Display Reviews
 *
 * 
 *
 * @link       https://yesreview.com
 * @since      1.0.0
 *
 * @package    Yes_Review
 * @subpackage Yes_Review/public/partials
 */
?>

<?php

$user_colors = array(
    "#1abc9c",
    "#2ecc71",
    "#3498db",
    "#9b59b6",
    "#34495e",
    "#16a085",
    "#27ae60",
    "#2980b9",
    "#8e44ad",
    "#2c3e50",
    "#f1c40f",
    "#e67e22",
    "#e74c3c",
    "#60bb46",
    "#95a5a6",
    "#f39c12",
    "#d35400",
    "#c0392b",
    "#bdc3c7",
    "#7f8c8d"
);

foreach ($returndata['data']['reviews'] as $data) {
    
    $acolor = $user_colors[mt_rand(0, count($user_colors) - 1)];
    $tmp_name = preg_replace("/[^A-Za-z]/", '', $data['name']);
    $initial = ! empty($tmp_name[0]) ? strtoupper($tmp_name[0]) : 'U';
    
    ?>


<div class="row media table-filter">

	<span class="step pull-left" style="background: <?php echo $acolor;?>"><?php echo $initial;?></span>
	<div class="media-body">

		<div class="col-sm-12 reviewtext ">
			<span class="<?php echo $data['rating'];?>_starcount"> <?php echo Yes_Review::displaystars($data['rating']);?> </span>
			<span class="text-green"> <strong><?php echo $data['name'];?> </strong></span>

			<span class=" " style="font-size: 12px;"> <?php echo !empty($data['date']) ? $data['date'] : '';//date("F j, Y");?> </span>

		</div>
		<div class="reviewtext col-sm-12"><?php echo htmlspecialchars($data['feedback']);?></div>
		<div class="col-xs-12 profilesite">
			<div class=" pull-right">
														<?php if($data['review_site'] == 'Google'){?>
														
														<?php $glink = $data['name'] == 'A Google User' ? $data['socialprofiles']['g_viewreview_url'] : "https://www.google.com/maps/contrib/".$data['review_uid']."/place/".$data['socialprofiles']['g_places_id']."/";?>
														<img
					src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/g.png';?>"
					alt="G" style="width: 25px;"><a title="" class="noicon"
					href="<?php echo $glink;?>" data-original-title="Overview"
					target="_blank" rel="nofollow"> Google <i class="fa fa-external-link"
					data-original-title="" title=""></i></a>
														
														<?php }elseif($data['review_site'] == 'facebook'){?>
														
					 									<img
					src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/f.png';?>"
					alt="F" style="width: 25px;"><a title="" class="noicon"
					href="<?php echo $data['socialprofiles']['fb_postreview_url'];?>"
					data-original-title="Overview" target="_blank" rel="nofollow"> Facebook <i
					class="fa fa-external-link" data-original-title="" title=""></i></a>
														
														<?php }elseif($data['review_site'] == 'yelp'){?>
														
														<img
					src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/y.png';?>"
					alt="Y" style="width: 25px;"><a title="" class="noicon"
					href="<?php echo $data['reviewer_url'];?>"
					data-original-title="Overview" target="_blank" rel="nofollow"> Yelp <i
					class="fa fa-external-link" data-original-title="" title=""></i></a>
														<?php }else{echo $data['review_site'];}?>
														
														</div>
		</div>
	</div>
</div>

<?php }

//this is an optional setting, if you don't want to display a link for Yes Review make sure the option is not checked (default) in settings.
if(!empty($returndata['data']['yesreview_optional'])){
    echo '<div class="row text-right" style="font-size:12px;"><a href="https://yesreview.com/" target="_blank">Yes Review</a></div>';
}


?>