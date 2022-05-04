# Fathom Analytics Conversions

[![GitHub issues](https://img.shields.io/github/issues/65/fathom-analytics-conversions)](https://github.com/65/fathom-analytics-conversions/issues) 

=== Fathom Analytics Conversions ===
Contributors: dloxton, khanhvo, fathomconversions
Donate link: https://www.fathomconversions.com
Tags: analytics, events, conversions, fathom, contactform7, wpforms
Requires at least: 5.9
Tested up to: 5.9
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add conversions in Wordpress plugins to Fathom Analytics

== Description ==

This plugin makes it easy to add conversions that you used to have in Google Analytics into [Fathom](https://usefathom.com) Analytics. Whilst they have produced a Wordpress plugin to make it easy to place their tracking code on the page, the ability to add conversions still requires some level of technical knowledge and this plugin aims to reduce that. 

Requirements:
*   A Fathom Account [$10 off your first month with our referral code](https://usefathom.com/ref/LBSJIU) - by using this you will support our development efforts and keeping this plugin free. OR Sign up for a [7 day free trial today](https://app.usefathom.com/register). Both options are $14/month thereafter on the basic plan.
*   A supported Wordpress plugin listed below

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

With the push to find another solution, we started with Fathom Analytics, and saw that it required plenty of code to integrate forms and create events and conversions. Hence, with their support, this plugin attempts to make it easy for the non-coders to implement events with Wordpress and Fathom Analytics. 

= How do I get started =

Upload the plugin according to the instructions. 

You will need [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) installed, and the official [Fathom Analytics](https://wordpress.org/plugins/fathom-analytics/) plugin installed.

Set up an Event on your Fathom account, and copy the Event ID into the Contact Form 7 settings. 

From there every submission will sent to Fathom as an Event. 

= I have a feature request = 

Please create a feature request in the Wordpress plugin support pages. 


= I have a bug or issue = 

Please create a thread in the Wordpress plugin support pages. 




== Screenshots ==

1. You can enable and disable integrations via the plugin settings. 
2. With Contact Form 7 you can create your Event in the Fathom Dashboard, and paste the Event ID here. 

== Changelog ==

= 1.0 =
* First Version supporting Contact Form 7


== Upgrade Notice ==

= 1.0 =
This is the first version.