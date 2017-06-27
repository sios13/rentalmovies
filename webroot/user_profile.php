<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$adminMeny = "";
if ($user->IsAdmin()) {
	$adminMeny = "
	<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
		<a href='admin.php'>Admin-sida</a>
	</nav>
	";
}

$p = isset($_GET['p']) ? $_GET['p'] : null;

if (isset($_POST['savetext'])) {
	$db->ExecuteQuery('UPDATE kmom07_Users SET text = ? WHERE name = ?', array($_POST['text'], $user->getName()));
	$db->SaveDebug();
	$_SESSION['user']->text = $_POST['text'];
	header("Location: user_profile.php");
}
if (isset($_POST['savename'])) {
	$db->ExecuteQuery('UPDATE kmom07_Users SET name = ? WHERE name = ?', array($_POST['name'], $user->getName()));
	$db->SaveDebug();
	$_SESSION['user']->name = $_POST['name'];
	header("Location: user_profile.php");
}
if (isset($_POST['savepass'])) {
	$db->ExecuteQuery('UPDATE kmom07_Users SET password = md5(concat(?, salt)) WHERE name = ?', array($_POST['pass'], $user->getName()));
	$db->SaveDebug();
	$_SESSION['user']->password = $_POST['pass'];
	header("Location: user_profile.php");
}

if (isset($_POST['logout'])) {
	$user->Logout();
	unset($user);
	header('Location: user_login.php');
}

$output = null;
if ($p == 'edit_text') {
	$output .= "<form method='post'>
	<fieldset>
	<legend>Redigera text</legend>
	<textarea name='text' cols='50' rows='10'>{$user->getText()}</textarea>
	<br>
	<input type='submit' name='savetext' value='Spara'/>
	</fieldset>
	</form>";
} else if ($p == "change_name") {
	$output .= "<form method='post'>
	<fieldset>
	<legend>Byt namn</legend>
	<p>Ange nytt namn</p>
	<input type='text' name='name' value='{$user->getName()}'/>
	<br>
	<input type='submit' name='savename' value='Spara'/>
	</fieldset>
	</form>";
} else if ($p == "change_password") {
	$output .= "<form method='post'>
	<fieldset>
	<legend>Byt lösenord</legend>
	<p>Ange nytt lösenord</p>
	<input type='password' name='pass' value=''/>
	<br>
	<input type='submit' name='savepass' value='Spara'/>
	</fieldset>
	</form>";
} else if ($p == "logout") {
	$output .= "<form method='post'>
	<fieldset>
	<legend>Logga ut</legend>
	<p><input type='submit' name='logout' value='Logga ut' /></p>
	</fieldset>
	</form>";
} else if ($p == "mymovies") {
	$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users WHERE name = ? LIMIT 1;", array($user->getName()));
	$movies = $res[0]->movies;
	$movies = array_filter(explode('.', $movies));
	$output .= "<h1>Mina filmer</h1>";
	if (sizeof($movies) > 0) {
		foreach($movies as $movie) {
			$output .= "<p>{$movie}</p>";
		}
	} else {
		$output .= "<p>Inga filmer hittades.</p>";
	}
} else {
	$output .= "<h1>{$user->getName()}</h1>";
	$output .= $user->getText();
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Profil";

$simox['main'] = "
<div id='ContentWrapper'>
	{$adminMeny}
	<div id='content-aside' style='float:left;'>
		<h1>{$user->getName()}</h1>
		<p><a href='user_profile.php' ".($p==''?"class='selected'":"").">Profil</a></p>
		<p><a href='?p=mymovies' ".($p=='mymovies'?"class='selected'":"").">Mina filmer</a></p>
		<br>
		<p><a href='?p=edit_text' ".($p=='edit_text'?"class='selected'":"").">Redigera text</a></p>
		<p><a href='?p=change_name' ".($p=='change_name'?"class='selected'":"").">Byt namn</a></p>
		<p><a href='?p=change_password' ".($p=='change_password'?"class='selected'":"").">Byt lösenord</a></p>
		<br>
		<p><a href='?p=logout' ".($p=='logout'?"class='selected'":"").">Logga ut</a></p>
	</div>
	<div id='content-info'>
		{$output}
	</div>
</div>
";


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);