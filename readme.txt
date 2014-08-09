=== Simple Parse Push Service ===
Contributors: dtsolis
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=8EBRPTZLMB6NQ&lc=US&item_name=Simple%20Parse%20Push%20Service%20WP%20Plugin&item_number=wp%2dplugin%2dsimpar§cy_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: parse, push notification, push, notification, mobile, smartphone, send, ios, android, channels
Requires at least: 3.3
Tested up to: 3.9.2
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple implementation for Parse.com Push Service (for iOS, Android, Windows 8 or any other devices may add).

== Description ==

This is a simple implementation for Parse.com Push Service (for iOS, Android, Windows 8 or any other devices may add). 
You can send a push notification via admin panel or with a post update/creation. In addition, you can include post's id as extra parameter to use it as you want with your mobile app, select the channels you want to receive notifications or add an extra parameter to the payload (any key/value pair you like).

**NOTICE:**
In order to use this plugin you MUST have an account with Parse.com and cURL ENABLED.

**WARNING:**
Wordpress, in order to publish a scheduled post, runs a pseudo-cron job WHEN a user visits the site. Any user, any page (admin page or a page a visitor sees).
SO, if for example you have scheduled a post for publish at 16:00 and this website doesn't have a hit until 16:50, the post will actually be published at 16:50 and the Push Notification will be sent at 16:50.<br/>
Keep that in mind!

== Installation ==

1. Upload `simple-parse-push-service` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Setup 'Application name', 'ApplicationID', 'REST API Key' according to your parse.com application

== Frequently asked questions ==


= cURL not working with my Windows x64 operating system =
Go to http://www.anindya.com/php-5-4-3-and-php-5-3-13-x64-64-bit-for-windows/ and download the curl version that corresponds to your php version under "Fixed curl extensions:".
So if you have php 5.3.13, download "php_curl-5.3.13-VC9-x64.zip". Try the "VC" version first. 

(Source: http://stackoverflow.com/questions/10939248/php-curl-not-working-wamp-on-windows-7-64-bit)

== Screenshots ==

1. Plugin's settings menu.
2. Plugin's settings menu (channels and post types to add a meta box).
3. Plugin's Push Notification dashboard
4. All push notifications for scheduled posts.
5. Add/Edit post, meta box

== Changelog ==

= 1.3 =
* Stability fixes
* Send Push Notification for scheduled posts
* Send Push Notification via 'edit menu' for any Post Type. Posts are enabled by default but any other Post Type can be selected from the Settings menu.

= 1.2 =
* Bug fix

= 1.1 =
* Ability to add channels or disable them completely
* Ability to change meta box priority
* Ability to add extra parameter (key/value pair) while sending a Push Notification using the plugin's dashboard
* UI changes

= 1.0.1 =
* Bug fix
* Ability to include post's id as extra parameter

= 1.0 =
* Auto-send Push Notification through parse.com service with every post publish

== Upgrade notice ==

Make sure you have cURL enabled in your server.
