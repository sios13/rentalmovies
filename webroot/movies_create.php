<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$title  = isset($_POST['title'])  ? strip_tags($_POST['title']) : null;
$create = isset($_POST['create']) ? true : false;

if ($create) {
	$sql = 'INSERT INTO kmom07_Movies (title, published, created) VALUES (?,NOW(),NOW())';
	$db->ExecuteQuery($sql, array($title));
	$db->SaveDebug();
	header('Location: movies_edit.php?id=' . $db->LastInsertId());
	exit;
}

$output = "
<h1>Lägg till film</h1>
<form method=post>
	<fieldset>
	<legend>Skapa ny film</legend>
	<p><label>Titel:<br/><input type='text' name='title'/></label></p>
	<p><input type='submit' name='create' value='Skapa'/></p>
	</fieldset>
</form>
";

if (!$user->IsAdmin()) {
	$output = "<p>Du måste vara administratör för att visa denna sida.</p>";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Lägg till film";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;'>
		{$adminAside}
	</div>
	<div id='content-info'>
		{$output}
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);