<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$create = isset($_POST['create']) ? true : false;

if ($create) {
	$name = isset($_POST['name']) ? $_POST['name'] : null;
	$type = isset($_POST['type']) ? $_POST['type'] : null;
	$text = isset($_POST['text']) ? $_POST['text'] : null;
	$db->ExecuteQuery("INSERT INTO kmom07_Users(name, type, text, salt) VALUES(?,?,?,unix_timestamp());", array($name, $type, $text));
	
	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$db->ExecuteQuery("UPDATE kmom07_Users SET password = md5(concat(?, salt)) WHERE name = ?", array($name, $name));
	
	header('Location: admin_users.php');
}

$output = null;
$output .= "<h1>Skapa användare</h1>";
$output .= "<form method='post'>
	<p>Namn<br><input type='text' name='name'/></p>
	<p>Type<br><input type='text' name='type'/>(user/admin)</p>
	<p>Text<br><textarea name='text' cols='50' rows='10'></textarea></p>
	<p>Lösenord<br><input type='password' name='password'/></p>
	<br><input type='submit' name='create' value='Spara'/>
</form>";

if (!$user->IsAdmin()) {
	$output = "Du måste vara administratör för att visa denna sida.";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Lägg till användare";

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