<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$name = isset($_GET['name']) ? $_GET['name'] : null;
$save = isset($_POST['save']) ? true : false;

if ($save && $name) {
	$userName = isset($_POST['name']) ? $_POST['name'] : null;
	$userType = isset($_POST['type']) ? $_POST['type'] : null;
	$userText = isset($_POST['text']) ? $_POST['text'] : null;
	
	$db->ExecuteQuery("UPDATE kmom07_Users SET
			name = ?,
			type = ?,
			text = ?
		WHERE
			name = ?;
	", array($userName, $userType, $userText, $name));
	
	$userPassword = isset($_POST['password']) ? $_POST['password'] : null;
	if ($userPassword) {
		$db->ExecuteQuery("UPDATE kmom07_Users SET password = md5(concat(?, salt)) WHERE name = ?", array($userName, $userName));
	}
}

$output = null;
$output .= "<h1>Uppdatera användare</h1>";
if ($name) {
	$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users WHERE name = ? LIMIT 1;", array($name));
	if (sizeof($res) == 1) {
		$output .= "<form method='post'>
			<p>Namn<br><input type='text' name='name' value='{$res[0]->name}'/></p>
			<p>Type<br><input type='text' name='type' value='{$res[0]->type}'/>(user/admin)</p>
			<p>Text<br><textarea name='text' cols='50' rows='10'>{$res[0]->text}</textarea></p>
			<p>Lösenord<br><input type='password' name='password'/></p>
			<br><input type='submit' name='save' value='Spara'/>
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
$simox['title'] = "Uppdatera användare";

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