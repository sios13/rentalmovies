<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$output = null;
// Check if user and password is okey
if (isset($_POST['name']) && isset($_POST['password'])) {
	$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users WHERE name = ? LIMIT 1;", array($_POST['name']));
	if (sizeof($res) == 0) {
		$salt = time();
		$db->ExecuteQuery('INSERT INTO kmom07_Users(name, salt) VALUES(?,?)', array($_POST['name'], $salt));
		$db->ExecuteQuery('UPDATE kmom07_Users SET password = md5(concat(?, salt)) WHERE name = ?', array($_POST['password'], $_POST['name']));
		$db->ExecuteQuery("UPDATE kmom07_Users SET text = 'Detta är en profiltext.' WHERE name = ?", array($_POST['name']));
		$output = "Användaren skapades!";
	} else {
		$output = "Användarnamnet är redan taget.";
	}
}

$form = <<<EOD
	<form method='post'>
	<fieldset>
	<p>
		<label>Ange användarnamn:<br>
		<input type='text' name='name' />
		</label>
	</p>
	<p>
		<label>Ange lösenord:<br>
		<input type='password' name='password' />
		</label>
	</p>
	<p>{$output}</p>
	<p><input type='submit' name='create' value='Skapa' /></p>
	</fieldset>
	</form>
EOD;

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Skapa användare";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;'>
		<p><a href='user_login.php'>Logga in</a></p>
		<p><a class='selected' href='user_create.php'>Skapa ny användare</a></p>
	</div>
	<div id='content-info'>
		<h1>Skapa användare</h1>
		{$form}
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);