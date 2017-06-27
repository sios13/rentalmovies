<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$output = null;
// Check if user and password is okey
if(isset($_POST['login'])) {
	$user->Login($_POST['name'], $_POST['password']);
	if (!$user->IsAuthenticated()) {
		$output = "Inloggningen misslyckades";
	}
}

$form = <<<EOD
	<form method='post'>
	<fieldset>
	<p>
		<label>Användarnamn:<br>
		<input type='text' name='name' />
		</label>
	</p>
	<p>
		<label>Lösenord:<br>
		<input type='password' name='password' />
		</label>
	</p>
	<p>{$output}</p>
	<p><input type='submit' name='login' value='Logga in' /></p>
	</fieldset>
	</form>
EOD;

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Logga in";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;'>
		<p><a class='selected' href='user_login.php'>Logga in</a></p>
		<p><a href='user_create.php'>Skapa ny användare</a></p>
	</div>
	<div id='content-info'>
		<h1>Logga in</h1>
		{$form}
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);