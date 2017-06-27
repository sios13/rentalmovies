<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$name = isset($_GET['name']) ? $_GET['name'] : null;

$output = null;
$outputAside = null;
$currentUser = null;
if (isset($name)) {
	$res = $db->ExecuteSelectQueryAndFetchAll('SELECT * FROM kmom07_Users WHERE name = ? LIMIT 1;', array($name));
	if (sizeof($res) == 1) {
		$currentUser = $res[0];
		$output .= "
			<h1>{$currentUser->name}</h1>
			<p>{$currentUser->text}</p>
		";
		$outputAside .= "<h1>{$currentUser->name}s filmer</h1>";
		$currentUserMovies = $currentUser->movies;
		$currentUserMovies = array_filter(explode('.', $currentUserMovies));
		if (sizeof($currentUserMovies) > 0) {
			foreach ($currentUserMovies as $movie) {
				$outputAside .= "<p>{$movie}</p>";
			}
		} else {
			$outputAside .= "<p>Inga filmer</p>";
		}
	} else {
		$output .= "<p>Det finns ingen användare med det namnet.</p>";
	}
} else {
	$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users;");
	$output = "<h1>Alla användare</h1>";
	foreach ($res as $currentUser) {
		$output .= "<p><a href='?name={$currentUser->name}'>{$currentUser->name}</a></p>";
	}
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = ($currentUser?$currentUser->name:'Användare');

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;'>
		{$outputAside}
	</div>
	<div id='content-info'>
		{$output}
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);