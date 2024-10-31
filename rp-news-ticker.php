<?php
/*
Plugin Name: RP News Ticker
Plugin URI: http://www.rationalplanet.com/php-related/rp-newsticker-plugin-for-wordpress.html
Description: A versatile horizontal news ticker for sidebars using <a href="http://www.gcmingati.net/wordpress/wp-content/lab/jquery/newsticker/jq-liscroll/scrollanimate.html" target="_blank">liScroll</a>. A must-have. Should be backward-compatible from 2.6+. Report bugs and submit ideas at <a href="http://www.rationalplanet.com/php-related/rp-newsticker-plugin-for-wordpress.html">the plugin's page</a>. (<em><strong>Previous version users: the widget setup is moved to <a href="options-general.php?page=rp-news-ticker.php">Settings-&gt;RP News Ticker</a></strong></em>)
Version: 0.7
Author: Alexander Missa
Author URI: http://www.rationalplanet.com
License: GPLv2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

//error_reporting(255);
//ini_set('display_errors', 'On');

if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define('RP_NEWS_TICKER_VERSION', '0.7');
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'rp-news-ticker-loader.php';
$instance = rp_news_ticker::gt();
$instance->init();
