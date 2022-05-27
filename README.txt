=== Fathom Analytics Conversions ===
Contributors: dloxton, khanhvo
Donate link: https://www.fathomconversions.com
Tags: analytics, events, conversions, fathom
Requires at least: 5.9
Tested up to: 6.0
Stable tag: 1.0.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add conversions in WordPress plugins to Fathom Analytics

== Description ==

This plugin makes it easy to add conversions from WordPress forms into [Fathom Analytics](https://usefathom.com) with no code.

The official Fathom Analytics plugin makes it easy to place the tracking code on the page, it does not add the ability to create events/conversions easily.

Fathom Analytics Conversions is the no code answer for WordPress and Fathom Analytics users. You need to technical knowledge to implement events.

= Key Features =

* Automated backend creation of events for your forms
* Code added to the form on submit to add the conversion in Fathom Analytics
* Events named by your form name
* Automatic update of the event Name if you change your form name

= Privacy Notices =

This plugin:

* does not track any users
* does not write any data to the database
* sends data to the Fathom Analytics servers - more information can be found on [Fathom Analytics](https://usefathom.com) website

= Demo =

You can find more information about the plugin, and see a demo and installation instructions on the [https://fathomconversions.com](Fathom Conversions website)

= Requirements =

For this to work you will need either:

1. You need a paid Fathom Analytics account [$10 off your first month with our referral code](https://usefathom.com/ref/LBSJIU) - by using this you will support our development efforts and keep this plugin free.
1. Sign up for a [7 day free trial today](https://app.usefathom.com/register). Both options are $14/month thereafter on the basic plan.

And a supported WordPress plugin listed below installed and active.

= Currently supported plugins =

*   [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)
*   [WPForms](https://wordpress.org/plugins/wpforms-lite/) & [WPForms Pro](https://wpforms.com)

== Installation ==

This section describes how to install the plugin and get it working.

Easy method:

1. From the WordPress dashboard Plugins > Add New search for 'Fathom Analytics' and click install then Activate
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Follow the instructions below to set up your API Key

Manual method:

1. Download the zip file from this page and unzip
1. Upload the entire `fathom-analytics-conversions` folder to the `/wp-content/plugins/` directory using your favourite FTP tool
1. Follow the instructions below to set up your API Key


== Frequently Asked Questions ==

= Why did you build this plugin? =

In January 2022 [Google Analytics was deemed illegal in the EU](https://www.sixfive.com.au/2022/02/austrian-dpa-rules-that-google-analytics-is-not-gdpr-compliant/) as data would be sent to Google server's in the US. this started our search for a more privacy focussed platform - we found Fathom Analytics.

In March 2022 [Google announced sunset July 1, 2023 and data deletion (+6 months) of Google Analytics Universal](https://www.searchenginejournal.com/google-sunsetting-universal-analytics-in-2023/442168/), with no data being kept essentially forcing users on to Google Analytics v4. This only complicates matters for the billions of sites using Google Analytics, and does not deal with the privacy requirements of EU users.

With the push to find another solution, we started with Fathom Analytics, and saw that it required plenty of code to integrate forms and create events and conversions. Hence, with their support, this plugin attempts to make it easy for the non-coders to implement events with  and Fathom Analytics.

= How do I get started? =

1. Start by creating your account on [Fathom Analytics](https://usefathom.com/ref/LBSJIU)
1. Install the official [Fathom Analytics](https://wordpress.org/plugins/fathom-analytics/) plugin, and configure it with your site ID.
1. Install or upload this plugin
1. Go to Settings > Fathom Analytics Conversions and follow the steps to create your API Key
1. Open the [Fathom Analytics API Settings](https://app.usefathom.com/#/settings/api) page
1. Create a new token, using a sensible name
1. Create as a 'Site-specific key'
1. Set Access to 'Manage' (this is because we need to create Events, not just track against them
1. Click Generate API Key
1. Copy the API Key and paste into Settings > Fathom Analytics Conversions
1. Click 'Save Changes'
1. Check the boxes for the installed plugins you want to track data from

The plugin will then go through these plugins and create the matching events. As soon as your form has a submission it will be recorded in your Fathom Analytics dashboard.


= I have a feature request = 

Please create a feature request in the [WordPress plugin support](https://wordpress.org/support/plugin/fathom-analytics-conversions/) pages.

= I have a bug or issue =

Please create a thread in the [WordPress plugin support](https://wordpress.org/support/plugin/fathom-analytics-conversions/) pages.

== Screenshots ==

1. Add your API Key here, and enable / disable integrations via the plugin settings.
2. We display the Event ID created by the plugin in the Fathom Dashboard, on a tab in Contact Form 7.
3. Events are created automatically in Fathom Analytics
4. You will need an API Key from Fathom Analytics

== Changelog ==

= 1.0.1 =
* Added deletion of plugin settings on plugin delete

= 1.0 =
* First version supporting Contact Form 7


== Upgrade Notice ==

= 1.0 =
This is the first version.
