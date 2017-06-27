<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$action = isset($_GET['action']) ? $_GET['action'] : null;
$diceGame = isset($_SESSION['dicegame']) ? $_SESSION['dicegame'] : new CDiceGame();

$output = "
<p>Besegra motståndaren i tärningsspelet 100 och vinn filmer!</p>
<p>Först till 100 vinner.</p>
<a href='?action=start'>Starta spelet</a>
";

if ($action == "quit") {
	$diceGame = null;
}

if ($diceGame && $user->IsAuthenticated()) {
	if ($action == "roll") {
		$diceGame->roll();
	}
	if ($action == "roundcomplete") {
		$diceGame->newRound();
	}
	if ($action == "start") {
		$diceGame = new CDiceGame();
	}
	$output = $diceGame->getOutput();
	if ($diceGame->player1Winner()) {
	
		$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies ORDER BY RAND() LIMIT 1;");
		$movie = $res[0]->title;
		$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users WHERE name = ?;", array($user->getName()));
		$userMovies = $res[0]->movies;
		$userMovies .= "." . $movie;
		$db->ExecuteQuery("UPDATE kmom07_Users SET movies = ? WHERE name = ?;", array($userMovies, $user->getName()));
		
		$output = "<p>Du vann!</p>";
		$output .= "<p>Du har vunnit filmen {$movie}!</p>";
		$output .= "<p><a href='?action=start'>Spela igen!</a></p>";
		$output .= "<p><a href='?action=quit'>Avsluta spelet</a></p>";
		
		$diceGame = null;
	}
}

if (!$user->IsAuthenticated()) {
	$output = "<p>Du måste vara inloggad för att tävla.</p><p><a href='user_login.php'>Logga in</a></p>";
}

$_SESSION['dicegame'] = $diceGame;

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Tävling";
 
$simox['main'] = "
<div id='ContentWrapper'>
	<div id='content-info'>
		<h1>Tävling</h1>
		{$output}
	</div>
	<div id='content-aside'>
	</div>
</div>
";

// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);