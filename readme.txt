=== Inpsyde Google Tag Manager ===
Contributors: inpsyde, chrico, vanvox
Tags: google, tag manager, gtm, analytics, data layer
Requires at least: 4.6
Tested up to: 4.9
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The Inpsyde Google Tag Manager adds the GTM Container Code to each site of your WordPress website and writes data into the data layer.

== Description ==

This plugin installs the free Google Tag Manager (GTM) on your WordPress website and writes website data into the data layer. With our plugin "Inpsyde Google Tag Manager" you don’t need to change the code in your theme manually anymore to use the Google Tag Manager. Our plugin takes over this task so that you can use the Google Tag Manager fast and easy.

The Google Tag Manager is a useful tool for website operators and is very popular both among online marketing experts and beginners. Denn bei richtiger Anwendung des Google Tag Managers unterstützt dich dieser bei der Optimierung deiner Webseite.  

Install all your marketing- and analytical tools comfortable via the user-friendly surface of the Google Tag Manager (GTM) by adding so-called tags - code snippets - in your GTM Container. In this way, and in most cases, you don’t need to change the code of your website anymore. So you don’t need to wait for a developer to change code anymore.

But code snippets for marketing and analytical tools like Google Analytics or Adwords are not the only things you can administrate via the Google Tag Manager. Moreover you can administrate any desired HTML or Javascript snippets you want to execute on your website.

Furthermore the Google Tag Manager provides a so-called data layer which helps to exchange the data between your website and the GTM. In the data layer there could, for example, be stored whether the visitor of your website is logged in or not. You could use these information to execute a tag (e.g. the Google Analytics tag) only in case the user is not logged in.

But in order to have the data written into the data layer, there normally needs to be a developer who has to code it. And once again, our plugin spares the developer, as the “Inpsyde Google Tag Manager” writes several data into the data layer - if you want that. Which these data are can be read in the section "Data Layer Output".

**Hinweis:** Der Inpsyde Google Tag Manager benötigt PHP ab Version 7.0 - dies ist die [von WordPress empfohlene PHP Version](https://wordpress.org/about/requirements/). Mehr dazu [hier](https://inpsyde.com/en/wordpress-recommended-php-version-update-php/).

= Best use cases for the Google Tag Manager =

* Integrate Google Analytics
* Collect user interaction in Google Analytics like clicks, sending forms, scroll, PDF downloads
* Set Adwords Conversion Tracking and Adwords Remarketing
* Display pop-ups to website visitors depending on several factors like the average stay on your site
* set Google Optimize for A/B testing

Welcome in the world of Digital Marketing & Measurement!

= Plugin Features =

* Installs the Google Tag Manager Container Code on your website.
* The `<noscript>` tag can be added automatically or via hook (see FAQ).
* Adds the GTM Container Code according to the latest Google guidelines.
* Transmits data into the data layer, at the moment information about user and site. For detailed information: see section  "Data Layer output".
* Renaming the dataLayer variable is possible
* The data layer outputs can be activated or deactivated separately. 
* Login status of website visitors visible in data layer.
* Suitable for WordPress MultiSite

= Data Layer Output =

**User**

* ID
* Role (inkl. output for not logged-in users)
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
* [Google Tag Manager Help Center](https://support.google.com/tagmanager/?hl=en#topic=3441530)
* [Official Google Tag Manager Forum](https://productforums.google.com/forum/#!forum/tag-manager)
* [Google Analytics Academy - Google Tag Manager Fundamentals Course ](https://analytics.google.com/analytics/academy/)

= Support =

You can find technical support for this plugin in the wordpress.org forum: [https://wordpress.org/support/plugin/inpsyde-google-tag-manager](https://wordpress.org/support/plugin/inpsyde-google-tag-manager)
Please read the FAQ (Frequently Asked Questions) first and make sure you have installed the newest version of the plugin before contacting us.

**Made by [Inpsyde](https://inpsyde.com) · We love WordPress**

== Installation ==

= Minimum Requirements =

* WordPress 4.6+
* PHP 7.0+

Furthermore, you need to sign up for a free [Google Tag Manager account](https://www.google.com/analytics/tag-manager/)

= Automatic Installation =

This is the easiest way to install the Inpsyde Google Tag Manager plugin.

1. Log into your WordPress installation.
2. Go to the menu item *Plugins* and then to *Install*.
3. Search for *Inpsyde Google Tag Manager*. In case several plugins are listed, check if *Inpsyde* is the plugin author.
4. Click *Install Now* and wait until WordPress reports the successful installation.
5. Activate the plugin. You can find the settings here: *Settings => Google Tag Manager*.
 
= Manual Installation =

In case the automatic installation doesn’t work, download the plugin from here via the *Download*-button. Unpack the archive and load the folder via FTP into the directory `wp-content\plugins` of your WordPress installation. Go to *Plugins => Installed plugins* and click *Activate* on *Inpsyde Google Tag Manager*.

== Frequently Asked Questions ==

= How do I create a Google Tag Manager account and Container? =

Before you can start with the Google Tag Manager, you need a GTM account and a Container. You can find the instructions here in the [GTM help](https://support.google.com/tagmanager/answer/6103696?hl=de&ref_topic=3441530). Then you install the Google Tag Manager via our plugin "Inspyde Google Tag Manager" on your website.

= Where do I find the Google Tag Manager ID? =

Visit the [start page of your Google Tag Manager](https://tagmanager.google.com/#/home). There you can find all your Google Tag Manager Accounts and the included Containers with their IDs.

= What means "Auto insert noscript in body"? =

The Google Tag Manager code consists of two parts. The first part belongs to the `<head>` of your website. The second part, the so-called `<noscript>` tag needs to be included after the opening `<body>` tag of your website. Unfortunately WordPress has no possibility to include the `<noscript>` tag safely after the opening `<body>`-tag as there is no WordPress hook for it.

Our plugin offers two possibilities:

* First possibility: The Inpsyde Google Tag Manager tries to include the code automatically after the opening `<body>` tag. But this method might collide with other plugins. 
* Second possibility: You add the hook `inpsyde-google-tag-manager.render-noscript` to your theme. The hook enables to include the `<noscript>`tag safely after the opening `<body>` tag.

= How do I add the hook for the noscript-Tag to my theme? =

1. Make a backup of the file `header.php` of the your theme in case something goes wrong.
2. Open the header.php of your theme, search the opening `<body>`tag and add the code `<?php do_action( "inpsyde-google-tag-manager.render-noscript" ); ?>`
   right after it.
3. Save the file and proof whether everything works correctly.

= What is the Google Tag Manager data layer? =

The data layer is a storage (the Javascript Array dataLayer[]), which helps to exchange information between your website and the Google Tag Manager. You can, for example, write into the data layer whether the visitor of your website is logged in or not. The GTM can use these information to carry out specific actions for visitors not being logged in. In case you implement Google Analytics Tracking via the Google Tag Manager, you can send data to Google Analytics for not logged in visitors only. 

= How do I write data of my website into the GTM data layer? =

The Inpsyde Google Tag Manager already writes several information into the data layer for you. Check out the section “Data Layer Output” to find out which are available at the moment. All you need to do is activating the writing of these data on the setting pages of our plugin. You’re missing something? Contact us!

= What are Google Tag Manager Tags, triggers and variables? =

The three most important phrases when implementing the Google Tag Manager are tags, triggers and variables. 

* *Tags* are code snippets which shall be executed on the website. These could be, for example, the snippet for the Google Analytics Tracking or the Adwords Conversion Tracking, or in other words, code snippets for the integration of third-party services. For some very popular ones there is an integration in GTM so that there is no need for the explicit code. Instead you can choose the type of tag in GTM and specify the parameters. But any desired HTML and Javascript snippets can be administrated and executed via the GTM. 
* *Triggers* specify when and where a tag shall be executed, for example on specific pages, at a specific timing, only for not logged in users etc. Nearly everything is possible.
* *Variables* are a name/value-pair, whereby the value is determined by the running time. They are used in tags and triggers. Example: If you implement the Google Analytics Tracking via GTM, you need to indicate the Google Analytics Property ID in the Analytics tags. To do this, you should create a variable and use this variable in the tags instead of the real Analytics Property ID.

= How do I use the data from the data layer in the GTM? =

When you create a variable in GTM, you can choose between a couple of variable types, amongst them is the type "Data Layer Variable". Choose this one. With the data layer variable’s name which you have to enter there you determine which information shall be read out. You get access to encapsulated variables with ".". You get the exact name from the data layer by e.g. checking out the GTM preview mode. 
Example: To get access to the user role with a GTM data layer variable, enter the name `user.role`.

= How do I set Google Analytics Tracking with the plugin "Inpsyde Google Tag Manager"? =

Implementing the Google Analytics Tracking is a common use case when using the Google Tag Manager. You can find a detailed instruction for the basic implementation in the [GTM help](https://support.google.com/analytics/answer/6163791?hl=de). For example: Control with the data layer being filled out with the plugin “Inpsyde Google Tag Manager”  for which user roles you want to set up the tracking. 

= How can I check whether my GTM tags work correctly? =

When setting up tags in GTM, you should always check whether they work the way you want. To identify problems, you can e.g. use these tools: 

* *Google Tag Manager preview mode* - you activate it in the working area of your GTM Container. More information [here](https://support.google.com/tagmanager/answer/6107056?hl=en)
* *Google Tag Assistant* - more information [here](https://get.google.com/tagassistant/)
* *Google Analytics Debugger* - this is a browser extension for debugging Google Analytics tracking code - available for Chrome and Firefox.
* *Browser developer tools* - for example you can see here in the console, if you have Javascript errors on your website which might impede the correct working of the GTM. 
 
= Which Hooks are provided by the plugin "Inpsyde Google Tag Manager"? =

You are WordPress developer and love its hook concept? At the moment our plugin offers these possibilities to hook into: 

* `inpsyde-google-tag-manager.error` - This action is triggered when an error occurs in our plugin.
* `inpsyde-google-tag-manager.debug` - This action is triggered for easier debugging of the plugin and provides useful information and context about the current page.
* `inpsyde-google-tag-manager.boot` - This action allows you to add your custom service providers and settings to the Plugin DI-container before booting all services.
* `inpsyde-google-tag-manager.before-script` - This action allows to insert custom markup before the gtm script-tag.
* `inpsyde-google-tag-manager.after-script` - This action allows to insert custom markup after the gtm script-tag.
* `inpsyde-google-tag-manager.render-noscript` - This action can be called manually in content to render the <noscript>-tag.
 
You can find more information for developers on our [Inpsyde Google Tag Manager Repository on github](https://github.com/inpsyde/inpsyde-google-tag-manager)

== Screenshots ==
 
1. Tab General - Add the Google Tag Manager ID, choose how the <noscript> tag shall be implemented.
2. Tab User - Choose which user information shall be transmitted in the data layer.
3. Tab Site info - Choose which site information shall be transmitted in the data layer. 
 
== Changelog ==
 
= 1.0 =
Initial Release
