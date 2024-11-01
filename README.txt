=== YesReview for WordPress ===
Contributors: yesreview
Donate link: https://yesreview.com/
Tags: local reviews, customer testimonials, yesreview, yes review, review
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 1.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display's business reviews from connected YesReview account.

== Description ==

[Yes Review](https://yesreview.com) is an automated platform that directs satisfied customers to relevant review sites to leave single-click feedback.  This plugin allows YesReview users to display their positive reviews from Google, Facebook and Yelp directly in their WordPress site. 

[youtube https://www.youtube.com/watch?v=iqETkmwYA-c]

Please Note: This plugin requires a private API key provided by YesReview in order to call a remote service hosted by YesReview to load your reviews.  No reviews will be loaded without a successful call to the remote service. 


== Installation ==

1. Upload `yesreview` plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place shortcode [yesreview ] in your content to display reviews.

== Frequently Asked Questions ==

= What remote API requests are made? =

Two requests are made to the YesReview service:

1) "https://yesreview.com/api/office/reviews" to pull recent reviews associated with your account.
2) "https://yesreview.com/api/office/locations" to pull available locations associated with your account.

= How do I get a YesReview API key? =

Existing customers can generate one through their yesreview.com account dashboard.  

If you are not currently a YesReview customer you can sign up for a demo at https://yesreview.com/

= Available shortcode options =

[yesreview profiles="Google, Yelp, Facebook" limit="25" minrating="4" locations="all"]



== Screenshots ==

1. YesReview settings and shortcode generator for the plugin. screenshot-1.jpg.  

== Changelog ==
= 1.2 =
* Added additional options. 

= 1.1 =
* Minor fix to properly display settings link. 

= 1.0 =
* Initial release.  

== Upgrade Notice ==
= 1.2 =
* Added additional options. 

= 1.1 =
* Minor fix to properly display settings link. 

= 1.0 =
* Initial release.  