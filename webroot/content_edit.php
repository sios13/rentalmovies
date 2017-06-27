<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$id = isset($_GET['id']) ? $_GET['id'] : null;
is_numeric($id) or die('Check: Id must be numeric.');

$sql = 'SELECT * FROM kmom07_Content WHERE id = ?';
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($id));

$content = new CContent($res[0]);
$content->setDb($db);
$content->setType('update');

$output = null;

$output .= "<h1>Uppdatera blog</h1>";
if (isset($_POST['save'])) {
	$output .= $content->execute();
}

$form = $content->makeForm();

if (!$user->IsAdmin()) {
	$output = "Du måste vara administratör för att visa denna sida.";
	$form = "";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Uppdatera blog";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;width:25%;'>
		{$adminAside}
	</div>
	<div id='content-info' style='width:75%;'>
		<p>{$output}</p>
		{$form}
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);