<?php
/**
 * Config-file for Simox. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly

/**
 * Define Simox paths.
 *
 */
define('SIMOX_INSTALL_PATH', __DIR__ . '/..');
define('SIMOX_THEME_PATH', SIMOX_INSTALL_PATH . '/theme/render.php');

/**
 * Include bootstrapping functions.
 *
 */
include(SIMOX_INSTALL_PATH . '/src/bootstrap.php');

/**
 * Start the session.
 *
 */
session_name(preg_replace('/[:\.\/-_]/', '', __DIR__));
session_start();

/**
 * Create the simox variable.
 *
 */
$simox = array();

/**
 * Site wide settings.
 *
 */
$simox['lang']         = 'sv';
$simox['title_append'] = ' | Simox en webbtemplate';

$simox['header'] = <<<EOD
<img class='sitelogo' src='img/superman.jpg' alt='Simox Logo'/>
<span class='sitetitle'>Simox webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;

$simox['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Simon Österdahl | <a href='https://github.com/mosbth/Anax-base'>Anax på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;

/**
 * Theme related settings.
 *
 */
//$simox['stylesheet'] = 'css/style.css';
$simox['stylesheets'] = array('css/style.css');
$simox['favicon'] = 'favicon.ico';

/**
 * Settings for JavaScript.
 *
 */
$simox['modernizr'] = 'js/modernizr.js';
$simox['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
//$simox['jquery'] = null; // To disable jQuery
$simox['javascript_include'] = array();
//$simox['javascript_include'] = array('js/main.js'); // To add extra javascript files

/**
 * Google analytics.
 *
 */
$simox['google_analytics'] = 'UA-22093351-1'; // Set to null to disable google analytics