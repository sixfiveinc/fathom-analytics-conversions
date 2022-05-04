=== Plugin Name ===
Contributors: dloxton, khanhvo
Donate link: https://www.sixfive.com.au
Tags: analytics, conversions, fathom, contactform7, wpforms
Requires at least: 5.9
Tested up to: 5.9
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add conversions in Wordpress plugins to Fathom Analytics

== Description ==

This plugin makes it easy to add conversions that you used to have in Google Analytics into [Fathom](https://usefathom.com) Analytics. Whilst they have produced a Wordpress plugin to make it easy to place their tracking code on the page, the ability to add conversions still requires some level of technical knowledge and this plugin aims to reduce that. 

Requirements:
* A Fathom Account [$10 off your first month with our referral code](https://usefathom.com/ref/LBSJIU) - by using this you will support our development efforts and keeping this plugin free. OR Sign up for a [7 day free trial today](https://app.usefathom.com/register). Both options are $14/month thereafter on the basic plan.
* A supported Wordpress plugin listed below

Currently supported plugins: 

*   [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the entire `fathom-analytics-conversions` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. The plugin will find what it can automatically add conversions for you and start pushing them in. 


== Frequently Asked Questions ==

= Why did you build this plugin? =

In January 2022 [Google Analytics was deemed illegal in the EU](https://www.sixfive.com.au/2022/02/austrian-dpa-rules-that-google-analytics-is-not-gdpr-compliant/) as data would be sent to Google server's in the US. this started oour search for a more privacy focussed platform and we found Fathom. 

In March 2022 [Google announced sunset July 1, 2023 and data deletion (+6 months) of Google Analytics Universal](https://www.searchenginejournal.com/google-sunsetting-universal-analytics-in-2023/442168/), with no data being kept essentialluy forcing users on to Google Analytics v4. This only complicates matters for the billions of sites using Google Analytics, and does not deal with the privacy requirements of EU users. 



== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 0.1 =
* First Version supporting Contact Form 7
