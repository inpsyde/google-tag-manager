# FAQ

## How do I create a Google Tag Manager Account and Container?

Before you can get started with Google Tag Manager, you'll need a GTM account and a container. Instructions can be found in [GTM Help](https://support.google.com/tagmanager/answer/6103696?hl=en&ref_topic=3441530). Then you install the Google Tag Manager via our plugin *Inspyde Google Tag Manager* on your website.

## Where can I find the Google Tag Manager ID?

Visit the [Google Tag Manager homepage](https://tagmanager.google.com/#/home). There you will find all your Google Tag Manager accounts and the included Containers with their IDs listed.

## What does "automatically insert noscript in body" mean?

The Google Tag Manager code consists of two parts. The first part belongs in the `<head>` of your website. The second part, the so-called `<noscript>` tag, must be inserted after the opening `<body>` tag of your website. Unfortunately, WordPress does not provide a way to safely insert the `<noscript>` tag after the opening `<body>` tag, since there is no WordPress hook for this.
Our plugin offers you two options:

* First Possibility: the Inpsyde Google Tag Manager will try to add the code automatically after the opening `<body>` tag. This method may possibly conflict with other plugins.
* Second Possibility: You can complement your theme with the hook `inpsyde-google-tag-manager.render-noscript`, which makes it possible to insert the `<noscript>` tag safely after the opening `<body>` tag.

## How do I add the hook for the noscript tag into my theme?

1. Very important! Make a backup of the `header.php` file of your theme in case something goes wrong.
2. Open the header.php of your theme, find the opening `<body>` tag and then add the code   `<? php do_action( "inpsyde-google-tag-manager.render-noscript" ); ?>`.
3. Save the file and then check if everything is still working properly.

## What is the Google Tag Manager Data Layer?

The Data Layer is a memory (the Javascript Array dataLayer[]) that is used to exchange information between your website and the Google Tag Manager. For example, you can write in the data layer to see if the visitor to your website is logged in or not. The GTM can use this information to perform certain actions only for non-logged-in visitors. For example, if you implement Google Analytics Tracking through Google Tag Manager, you will only send data to Google Analytics for non-logged-in visitors.

## How do I write data from my website into the GTM Data Layer?

The Inpsyde Google Tag Manager writes certain information into the data layer for you. Exactly what is currently available can be found in the description in the section "Data Layer Outputs". You only have to activate the writing of the individual data on the settings pages of the plugin. You miss something? Get in contact with us!

## What are Google Tag Manager Tags, Triggers, and Variables? 

The three key terms in implementing Google Tag Manager are tags, triggers and variables.

* *Tags* are code snippets that should be executed on the website. For example, the snippet for Google Analytics tracking or for Adwords Conversion Tracking, code snippets for integrating third-party services. For some acquaintances, there is an integration in the GTM, so that here no longer the code must be explicitly specified, but the type of tag is selected in the GTM and there the parameters are set. But also any HTML and JavaScript snippets can be managed and executed with the GTM.

* *Triggers* determine when a tag should be executed, for example, only on certain pages, at certain times, only for logged-in users, etc. There are almost no limits to the imagination.

* *Variables* are a name/value pair, with the value set at runtime. They are used in tags and triggers. Example: If you implement Google Analytics tracking via the GTM, you must enter the Google Analytics Property ID in the Analytics Tags. To do this, it's best to create a variable and not use the actual Analytics Property ID in the tags, but the variable created for it.

## How do I use the data from the data layer in the GTM?

When creating a variable in the GTM, certain variable types are available to you, including the variable type "Data Layer Variable". Select this. Via the name of the data layer variables you specify which information is to be read from the data layer. For nested variables, you're using "." towards this. The exact name you take from the Data Layer, which you can see for example in the GTM preview mode.
Example: To access the user role with a GTM data layer variable, enter the name `user.role`.

## How do I set up Google Analytics tracking with the "Inpsyde Google Tag Manager" plugin?

Implementing Google Analytics tracking is a common use case for using Google Tag Manager. Instructions for a basic implementation can be found in [GTM Help](https://support.google.com/analytics/answer/6163791?hl=en). Control over the data layer that the plugin "Inpsyde Google Tag Manager" fills, for example for which user roles the tracking should take place.

## How can I check if my GTM tags are working properly?

When you set up tags in the GTM, you should always check that they work as intended. To identify problems, you can use the following tools, for example:

* *Google Tag Manager Preview Mode* - activate this in the working area of your GTM container. More [here](https://support.google.com/tagmanager/answer/6107056?hl=en)
* *Google Tag Assistant* - read more [here](https://get.google.com/tagassistant/)
* *Google Analytics Debugger* - this is a browser extension for debugging the Google Analytics tracking code - available for Chrome and Firefox.
* *Browser Developer Tools* - here you can see in the console, for example, if javascript errors appear on your site that could prevent the GTM tag from working properly.

## Which hooks are provided by the plugin "Inpsyde Google Tag Manager"? 

You are a WordPress developer and love the hook concept of WordPress? Currently our plugin offers you the following possibilities to hook you in [02-hooks.md](./02-hooks.md).
