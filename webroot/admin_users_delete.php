<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$name = isset($_GET['name']) ? $_GET['name'] : null;
$delete = isset($_POST['delete']) ? true : false;

if ($delete && $name) {
	$db->ExecuteQuery("DELETE FROM kmom07_Users WHERE name = ? LIMIT 1;", array($name));
	header('Location: admin_users.php');
}

$output = null;
$output .= "<h1>Radera användare</h1>";
if ($name) {
	$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users WHERE name = ? LIMIT 1;", array($name));
	if (sizeof($res) == 1) {
		$output .= "<form method='post'>
			<p>Vill du radera {$res[0]->name}?</p>
			<br><input type='submit' name='delete' value='Radera'/>
		</form>";
	} else {
		$output .= "Ingen användare med det namnet hittades.";
	}
} else {
	$output .= "Inget namn har angetts.";
}

if (!$user->IsAdmin()) {
	$output = "Du måste vara administratör för att visa denna sida.";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Radera användare";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style=''>
		{$adminAside}
	</div>
	<div id='content-info'>
		{$output}
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);