=== Inpsyde Google Tag Manager ===
Contributors: inpsyde, chrico, vanvox
Tags: google, tag manager, gtm, analytics, data layer
Requires at least: 4.6
Tested up to: 4.9
Requires PHP: 7.0
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Inpsyde Google Tag Manager inserts the GTM Container Code on every page of your WordPress site and writes data to the Data Layer.

== Description ==

This plugin installs the free Google Tag Manager (GTM) on your WordPress website and writes website data into the data layer. With our plugin "Inpsyde Google Tag Manager" you no longer need to manually change the code in the theme as a user to use the Google Tag Manager. This is done by the plugin, so you can use Google Tag Manager easily and quickly.

The Google Tag Manager is a very helpful tool for website owners and very popular with both online marketing experts and newcomers. If you use Google Tag Manager correctly, it will help you optimize your website.

Easily install all of your marketing and analytics tools through the easy-to-use interface of the Google Tag Manager (GTM) by tagging them in your GTM container as tags or so-called "Code-Snippets". In many cases you do not have to change the code of your website. The wait for the developer has an end.

Not only code snippets for marketing and analytics tools such as Google Analytics or Adwords can be managed through Google Tag Manager, but also any HTML or JavaScript snippets you want to run on your website.

In addition, the Google Tag Manager provides a so-called data layer, which is used to exchange data between your website and the GTM. For example, it could be stored in the Data Layer whether the visitor to your website is logged in or not. You could use this information to execute a tag (for example, the Google Analytics tag) only if the visitor is not logged in.

However, in order for the data to be written to the data layer, this usually needs to be programmed by a developer. And this is exactly where our plugin comes into play a second time and saves you the developer again. Because our plugin "Inpsyde Google Tag Manager" writes certain data for you in the Data Layer, if you want it. You will find out exactly what these are in the section "Data Layer Output".

**Note:** Inpsyde Google Tag Manager requires PHP version 7.0 or higher - this is the [WordPress Recommended PHP Version](https://wordpress.org/about/requirements/). More [here](https://inpsyde.com/en/wordpress-recommended-php-version-update-php/).

= Top use cases for Google Tag Manager =

* Integrate Google Analytics
* Capturing user interactions in Google Analytics such as clicks, submitting forms, scrolling, PDF downloads
* Set up Adwords Conversion Tracking and Adwords Remarketing
* Play pop-ups on website visitors depending on various factors, such as time spent on the page
* Setting up Google Optimize for A/B Testing

Welcome to the world of Digital Marketing & Measurement!

= Plugin Features =

* Installs the Google Tag Manager Container Code on your website.
* The `<noscript>` tag can be inserted automatically or via hook (see FAQ).
* Inserts the GTM container code according to Google's current guidelines.
* Output of data to the data layer, currently information about user and site, for details see section "Data Layer Outputs".
* Rename the dataLayer variable possible.
* The data layer outputs can be turned on and off individually.
* Login status of website visitors in the Data Layer recognizable.
* Suitable for WordPress MultiSite

= Data Layer Outputs =

**User**

* ID
* Role (including output for non-logged in users)
* Nickname
* Description
* First name
* Last name
* E-Mail
* Url

**Site info**

* Blog information:

   * Name
   * Description
   * Url
   * Charset
   * Language

* MultiSite information:

  * ID
  * Network ID
  * Blog name
  * Site url
  * Home


= Official Google Tag Manager Links =

* [Google Tag Manager Website](https://www.google.com/analytics/tag-manager/)
* [Google Tag Manager Help](https://support.google.com/tagmanager/?hl=en#topic=3441530)
* [Official Google Tag Manager Forum](https://productforums.google.com/forum/#!forum/tag-manager)
* [Google Analytics Academy - Google Tag Manager Fundamentals Course](https://analytics.google.com/analytics/academy/)

= Support =

This plugin comes as it is and free as in speech, you can do whatever you want with it, but don't expect free support or adjustments to your liking. If you need help, we are glad to help, if we have time to. Or you can hire us for your needs.

**Made by [Inpsyde](https://inpsyde.com) Â· We love WordPress**

== Installation ==

= Minimum Requirements =

* WordPress 4.6+
* PHP 7.0+

In addition, you'll need a free [Google Tag Manager Account](https://www.google.com/analytics/tag-manager/).

= Automatic Installation =

This is the easiest way to install the Inpsyde Google Tag Manager plugin.

1. Log in to your WordPress installation.
2. Go to the menu item *Plugins* and there *Install*.
3. Search *Inpsyde Google Tag Manager*. If you get multiple plugins listed, make sure the plugin author is *Inpsyde*.
4. Click on *Install Now* and wait for WordPress to report the successful installation.
5. Then activate the plugin. The settings can be found under *Settings => Google Tag Manager*.

= Manual Installation =

If the automatic installation does not work for you, download the plugin here via the Download button. Unzip the archive and upload the folder via FTP into the directory `wp-content\plugins` of your WordPress installation.
Go to *Plugins => Installed Plugins* and click *Activate* on *Inpsyde Google Tag Manager*.

 == FAQ ==

= How do I create a Google Tag Manager Account and Container? =

Before you can get started with Google Tag Manager, you'll need a GTM account and a container. Instructions can be found in [GTM Help](https://support.google.com/tagmanager/answer/6103696?hl=en&ref_topic=3441530). Then you install the Google Tag Manager via our plugin *Inspyde Google Tag Manager* on your website.

= Where can I find the Google Tag Manager ID? =

Visit the [Google Tag Manager homepage](https://tagmanager.google.com/#/home). There you will find all your Google Tag Manager accounts and the included Containers with their IDs listed.

= What does "automatically insert noscript in body" mean? =

The Google Tag Manager code consists of two parts. The first part belongs in the `<head>` of your website. The second part, the so-called `<noscript>` tag, must be inserted after the opening `<body>` tag of your website. Unfortunately, WordPress does not provide a way to safely insert the `<noscript>` tag after the opening `<body>` tag, since there is no WordPress hook for this.
Our plugin offers you two options:

* First Possibility: the Inpsyde Google Tag Manager will try to add the code automatically after the opening `<body>` tag. This method may possibly conflict with other plugins.
* Second Possibility: You can complement your theme with the hook `inpsyde-google-tag-manager.render-noscript`, which makes it possible to insert the `<noscript>` tag safely after the opening `<body>` tag.

= How do I add the hook for the noscript tag into my theme? =

1. Very important! Make a backup of the `header.php` file of your theme in case something goes wrong.
2. Open the header.php of your theme, find the opening `<body>` tag and then add the code   `<? php do_action( "inpsyde-google-tag-manager.render-noscript" ); ?>`.
3. Save the file and then check if everything is still working properly.

= What is the Google Tag Manager Data Layer? =

The Data Layer is a memory (the Javascript Array dataLayer[]) that is used to exchange information between your website and the Google Tag Manager. For example, you can write in the data layer to see if the visitor to your website is logged in or not. The GTM can use this information to perform certain actions only for non-logged-in visitors. For example, if you implement Google Analytics Tracking through Google Tag Manager, you will only send data to Google Analytics for non-logged-in visitors.

= How do I write data from my website into the GTM Data Layer? =

The Inpsyde Google Tag Manager writes certain information into the data layer for you. Exactly what is currently available can be found in the description in the section "Data Layer Outputs". You only have to activate the writing of the individual data on the settings pages of the plugin. You miss something? Get in contact with us!

= What are Google Tag Manager Tags, Triggers, and Variables? =

The three key terms in implementing Google Tag Manager are tags, triggers and variables.

* *Tags* are code snippets that should be executed on the website. For example, the snippet for Google Analytics tracking or for Adwords Conversion Tracking, code snippets for integrating third-party services. For some acquaintances, there is an integration in the GTM, so that here no longer the code must be explicitly specified, but the type of tag is selected in the GTM and there the parameters are set. But also any HTML and JavaScript snippets can be managed and executed with the GTM.

* *Triggers* determine when a tag should be executed, for example, only on certain pages, at certain times, only for logged-in users, etc. There are almost no limits to the imagination.

* *Variables* are a name/value pair, with the value set at runtime. They are used in tags and triggers. Example: If you implement Google Analytics tracking via the GTM, you must enter the Google Analytics Property ID in the Analytics Tags. To do this, it's best to create a variable and not use the actual Analytics Property ID in the tags, but the variable created for it.

= How do I use the data from the data layer in the GTM? =

When creating a variable in the GTM, certain variable types are available to you, including the variable type "Data Layer Variable". Select this. Via the name of the data layer variables you specify which information is to be read from the data layer. For nested variables, you're using "." towards this. The exact name you take from the Data Layer, which you can see for example in the GTM preview mode.
Example: To access the user role with a GTM data layer variable, enter the name `user.role`.

= How do I set up Google Analytics tracking with the "Inpsyde Google Tag Manager" plugin? =

Implementing Google Analytics tracking is a common use case for using Google Tag Manager. Instructions for a basic implementation can be found in [GTM Help](https://support.google.com/analytics/answer/6163791?hl=en). Control over the data layer that the plugin "Inpsyde Google Tag Manager" fills, for example for which user roles the tracking should take place.

= How can I check if my GTM tags are working properly? =

When you set up tags in the GTM, you should always check that they work as intended. To identify problems, you can use the following tools, for example:

* *Google Tag Manager Preview Mode* - activate this in the working area of â€‹â€‹your GTM container. More [here](https://support.google.com/tagmanager/answer/6107056?hl=en)
* *Google Tag Assistant* - read more [here](https://get.google.com/tagassistant/)
* *Google Analytics Debugger* - this is a browser extension for debugging the Google Analytics tracking code - available for Chrome and Firefox.
* *Browser Developer Tools* - here you can see in the console, for example, if javascript errors appear on your site that could prevent the GTM tag from working properly.

= Which hooks are provided by the plugin "Inpsyde Google Tag Manager"? =

You are a WordPress developer and love the hook concept of WordPress? Currently our plugin offers you the following possibilities to hook you in:

* `inpsyde-google-tag-manager.error` - This action is triggered when an error occurs in our plugin.
* `inpsyde-google-tag-manager.debug` - This action can be used to facilitate debugging and provides helpful information about the current page.
* `inpsyde-google-tag-manager.boot` - This action allows you to add your own service providers and settings to the plugin DI-container.
* `inpsyde-google-tag-manager.before-script` - This action allows you to insert custom markup before the GTM script tag.
* `inpsyde-google-tag-manager.after-script` - This action allows you to insert custom markup after the GTM script tag.
* `inpsyde-google-tag-manager.render-noscript` - This action can be called manually in content to render the `<noscript>` tag.

More info for developers can be found in our [Inpsyde Google Tag Manager Repository at github](https://github.com/inpsyde/inpsyde-google-tag-manager)

== Screenshots ==

1. Tab General - Insert the Google Tag Manager ID and choose how to insert the noscript-tag.
2. Tab User - Select which user information should be output to the data layer.
3. Tab Site info - Select which page information to output to the data layer.

== Changelog ==


= 1.2 =

== Updated ==
- Updated `inpsyde/php-coding-standards` to version `~0.7`.
- Updated several methods according due the coding standard.

= 1.1 =

== Updated ==
- Updated `readme.txt`.
- Updated `chrico/wp-fields` to version `~0.3`.

== Fixed ==
- Fixed duplicated `<code>` in backend form description for noscript-tag.

== Improvements ==
- Allow saving empty "User > visitorRole" and don't show empty `user.role`.
- Updated tests and code according to the new `chrico/wp-fields`-version.
- Introduced new `Http\ParameterBag` and `Http\Request`.
- Removed `filter_input`-usage which causes empty data in various PHP-versions.
- Improved description for multisite-field for easier translation.
- Moved to new [Inpsyde PHP Coding standard](https://github.com/inpsyde/php-coding-standards).

= 1.0 =
First release
