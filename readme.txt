=== RP News Ticker ===

Contributors: alecksmart
Donate link: http://www.rationalplanet.com/php-related/rp-newsticker-plugin-for-wordpress.html
Tags: news, ticker, widget, horizontal, jQuery, scroll, scroller, post, page, sidebar, shortcut, tag
Requires at least: 2.6
Tested up to: 3.2
Stable tag: tags/0.7

A versatile horizontal news ticker using liScroll.js

== Description ==

The plugin creates a shortcode to be used in a widget to display a nice horizontal newsticker which can be placed in a sidebar.
Since 0.7: one can use a php tag or shortcode to insert it in the theme template, widgets or posts, all tuning moved to Settings
Based upon liScroll.js by Gian Carlo Mingati.
Tested in Wordpress 3.0+ only, expected to be compatible from 2.6+.
Demo: http://chernivtsi.ws (in Ukrainian).

= Features: =

 = Show latest posts titles in a scrolling newsticker =

    1. A number of latest post can be tuned in settings
    2. Categories can be defined with a standard syntax, see Installation for details
    3. Choose date format for category posts
    4. Tune css from settings
    5. Insert php tag into theme template files
    6. Ukrainian and Russian localization

  = Alternatively show show your own content in a scrolling newsticker =

    1. Unlimited number of explicitly given elements to scroll
    2. Mix your explicitly given content with posts
    3. Choose what to show: posts, explicit content, both in order or randomized

== Credits ==

liScroll, Copyright 2007-2010 Gian Carlo Mingati,
http://www.gcmingati.net/wordpress/wp-content/lab/jquery/newsticker/jq-liscroll/scrollanimate.html
Dual licensed under the MIT and GPL licenses.

RP News Ticker, Copyright 2007-2010 Alexander Missa,
http://www.rationalplanet.com
Dual licensed under the MIT and GPL licenses.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Belarusian Translation 
Alexander Ovsov 
http://webhostinggeeks.com/science/">Web Geek Sciense

== Installation ==

1. Upload the folder rp-news-ticker with its contents to wp-content/plugins/

2. Activate the plugin

3. Proceed to Settings -> RP News Ticker section to configure; use anywhere with with a shortcode or a php tag.

4. Optionally:
    1) Create your language file from rp-news-ticker.pot.

5. Category IDs syntax:
    1) Leave empty for all posts in all categories
    2) "5,7" - include only posts in categories with ids 5 and 7 (no quotes please)
    3) "-5,-7" - include posts in all categories but exclude posts of categories with ids 5 and 7

== Upgrade Notice ==

  = 0.6 to 0.7 =

    Your previous version widget will be unusable, however, the plugin will attempt to preserve the previous setup. Backup your current settings from the wigdet to a text file, than navigate to "Settings->RP New Ticker" for the new setup.

  = 0.5 to 0.6 =

    The release is compatible with the previous version.

  = 0.4 to 0.5 =

    The release is compatible with the previous version.

  = 0.3 to 0.4 =

    The release is compatible with the previous version.

  = 0.2 to 0.3 =

    The release is compatible with the previous version.

  = 0.1 to 0.2 =

    Please completely uninstall the previous version removing all files.

== Screenshots ==

[Visit the plugins page for screenshots](http://www.rationalplanet.com/php-related/rp-newsticker-plugin-for-wordpress.html).

[Live site which uses the plugin](http://chernivtsi.ws/) (in Ukrainian).


== Frequently Asked Questions ==

Question:   Will this plugin work with versions of WordPress earlier than 3.0?

Answer:     The plugin has only been tested with version 3.0 and above. Please report any bugs via [developer's web site] (http://www.rationalplanet.com/).

Question:   What is RP in plugin's name?

Answer:     It stands for [rationalplanet.com] (http://www.rationalplanet.com/), just the way to distinguish our plugins from zillions of others.


Question:   When is the newer version out?

Answer:     The best way to stimulate it is to send a donation from the developer's site. Or just visit a developer's site.


== Changelog ==

 = 0.7 =

 Moved settings from widget to options; added shortcode support; added theme tag support; added Russian localization.

 = 0.6 =

 Skipped release.

 = 0.5 =

 Removed the bug that disallowed including/excluding categories that contain 0.

 = 0.4 =

 Allow arbitrary data to be mixed with posts data.

 = 0.3 =

 Allow arbitrary data instead of posts.

 Reset options to default.

 = 0.2 =

 Restructured file names to a more logical pattern.

 Tuning css from the widget window.

 = 0.1 =

 Initial version, core functionality, least features.

== Upcoming Features ==

 * allow to set scroller speed
 * choose appear effect on page load
 * allow multiple instances of the widget
 * allow periodical refresh with ajax
