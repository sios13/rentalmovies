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
$simox['title_append'] = ' | RM Rental Movies';

/**
 * Settings for the database.
 *
 */
$simox['database']['dsn']            = 'mysql:host=localhost;dbname=rentalmovies'; //mysql:host=blu-ray.student.bth.se;dbname=sios13;
$simox['database']['username']       = 'root'; //sios13
$simox['database']['password']       = ''; //I923{4En
$simox['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

//User
$db = new CDatabase($simox['database']);
$user = new CUser($db);

function modifyNavbar($items) {
  $ref = isset($_GET['p']) && isset($items[$_GET['p']]) ? $_GET['p'] : null;
  if($ref) {
    $items[$ref]['class'] .= 'selected'; 
  }
  return $items;
}

if ($user->IsAuthenticated()) {
	$menu = array(
	  'callback' => 'modifyNavbar',
	  'items' => array(
		  'index'  => array('text'=>'RM Rental Movies', 'url'=>'index.php?p=index', 'class'=>null),
		  'movies' => array('text'=>'FILMER',           'url'=>'movies.php?p=movies&amp;orderby=title&amp;order=asc', 'class'=>null),
		  'news'  => array('text'=>'NYHETER',           'url'=>'content_blog.php?p=news', 'class'=>null),
		  'about' => array('text'=>'OM',                'url'=>'content_page.php?url=om&amp;p=about', 'class'=>null),
		  'profile' => array('text'=>'PROFIL',          'url'=>'user_profile.php?p=profile', 'class'=>null),
		  'dice' => array('text'=>'TÄVLING',            'url'=>'dice.php?action=quit&amp;p=dice', 'class'=>null),
		  'users' => array('text'=>'ANVÄNDARE',            'url'=>'users.php?p=users', 'class'=>null),
		  'source' => array('text'=>'KÄLLKOD',          'url'=>'source.php?p=source', 'class'=>null),
	  ),
	);
} else {
	$menu = array(
	  'callback' => 'modifyNavbar',
	  'items' => array(
		  'index'  => array('text'=>'RM Rental Movies', 'url'=>'index.php?p=index', 'class'=>null),
		  'movies' => array('text'=>'FILMER',           'url'=>'movies.php?p=movies&amp;orderby=title&amp;order=asc', 'class'=>null),
		  'news'  => array('text'=>'NYHETER',           'url'=>'content_blog.php?p=news', 'class'=>null),
		  'about' => array('text'=>'OM',                'url'=>'content_page.php?url=om&amp;p=about', 'class'=>null),
		  'login' => array('text'=>'LOGGA IN',          'url'=>'user_login.php?p=login', 'class'=>null),
		  'dice' => array('text'=>'TÄVLING',            'url'=>'dice.php?action=quit&amp;p=dice', 'class'=>null),
		  'users' => array('text'=>'ANVÄNDARE',            'url'=>'users.php?p=users', 'class'=>null),
		  'source' => array('text'=>'KÄLLKOD',          'url'=>'source.php?p=source', 'class'=>null),
	  ),
	);
}

//Navbar
$navbar = new CNavigation();
$navbarStr = $navbar->GenerateMenu($menu, "navbar");

$simox['header'] = <<<EOD
{$navbarStr}
EOD;

$simox['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) RM Rental Movies | <a href='https://github.com/sios13/simox'>Simox på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
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

// Admin-aside
$adminAside = "
	<h2>Filmer</h2>
	<p><a href='movies_edit.php'>Uppdatera/Radera film</a></p>
	<p><a href='movies_create.php'>Lägg till film</a></p>
	<h2>Nyheter</h2>
	<p><a href='content.php'>Uppdatera/Radera nyhet</a></p>
	<p><a href='content_create.php'>Lägg till nyhet</a></p>
	<h2>Om</h2>
	<p><a href='content_edit.php?id=1'>Uppdatera</a></p>
	<h2>Användare</h2>
	<p><a href='admin_users.php'>Uppdatera/Radera användare</a></p>
	<p><a href='admin_users_create.php'>Lägg till användare</a></p>
	
";