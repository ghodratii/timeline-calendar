=== Timeline Calendar ===
Contributors: omidkorat
Donate link: http://www.dementor.ir
Tags: timeline, calendar, event, widget
Requires at least: 3
Tested up to: 3.2
Stable tag: 1.2

A plugin for make your own timeline calendar with lots of options.

== Description ==

A plugin for make your own timeline calendar. With this plugin, you can add your own events and show them via widget or special php code in your template. This plugin displays your events in specified dates (that you already set). For instant if your birthday is February 3, in Feb 3 your birthday message will be display. In addition you can display yesterday and tomorrow events too.

There is a special widget along with this plugin and also there are lots of options like customizing display mode, possibility of using html codes, edit or delete events and completely uninstall plugin data.

This plugin has been made based on standard and Hijri Shamsi calendar.

= Features =
* Add, edit and delete your own events.
* Add multiple events for one day. (NEW)
* Totally customizable in HTML for all days.
* Ability to hide in days with no events.
* Ability to displaying an excerpt instead of full event text.
* Ability to show event just for today or past and future days.
* 100% compatible with وردپرس فارسی and Jalali calendar.
* With special uninstall page.

== Installation ==

1. Upload plugin folder to the `/wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php if (function_exists('mytimeline')): mytimeline(); endif; ?>` in your templates or enable 'Timeline Calendar' from your widgets

== Frequently Asked Questions ==

= How can I add Timeline Calendar into my page? =

Place `<?php if (function_exists('mytimeline')): mytimeline(); endif; ?>` in your page template.

= Where is the settings page for Timeline Calendar? =

Log into WordPress and go to Timeline\Timeline.


== Screenshots ==

1. Settings page
2. Add events
3. Widget in English
4. Widget in Persian


== Changelog ==

= 1.2 =
* Ability to add multiple events for one day.
* Reorganize options.
* Optimize codes.

= 1.1.2 =
* Fix HTML rendering in excerpt mode.
* Translations for Persian has been updated.

= 1.1.1 =
* Fix HTML rendering in widget and PHP code.
* Fix HTML rendering in Events page.

= 1.1 =
* Auto enable of Jalali Calendar when wp-jalali is enable.
* Display correct form of Farsi numbers when converting number has been enable in wp-jalali.
* Fix empty displaying of calendar when no record found for three days or today.
* From now, if display method has been set on today, "Hide empty days" feature will not work anymore.
* Fix removing new options on uninstall page.
* Translations for English has been updated.

= 1.0.3 =
* Added an option for displaying an excerpt instead of full event text.
* Added an option for specify character  limit for excerpt.

= 1.0.2 =
* Added an option for converting line breaks to paragraph.
* Fix removing files after deactivating.
* Translations for Persian and English have been updated.

= 1.0.1 =
* Fix icon and Persian language.

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.2 =
* Ability to add multiple events for one day.