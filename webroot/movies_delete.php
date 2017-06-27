<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$delete = isset($_POST['delete']) ? true : false;
$id = isset($_GET['id']) ? strip_tags($_GET['id'])-1 : null;

$output = null;
if ($delete) {
	$sql = "DELETE FROM kmom07_Movies WHERE id = ? LIMIT 1;";
	$db->ExecuteQuery($sql, array($id));
	$db->SaveDebug("Det raderades " . $db->RowCount() . " rader från databasen.");
	header('Location: movies_delete.php');
}

if (isset($_GET['id'])) {
	$sql = "SELECT * FROM kmom07_Movies WHERE id = ? LIMIT 1;";
	$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($_GET['id']));
	
	if (sizeof($res) == 1) {
		$output = "<form method=post>
			<fieldset>
			<legend>Ta bort film</legend>
			<p>Vill du ta bort {$res[0]->title}?</p>
			<p><input type='submit' name='delete' value='Ta bort'/></p>
			</fieldset>
		</form>";
	} else {
		$output = "<p>Ingen film hittades.</p>";
	}
} else {
	$output = "<p>Inget id har angetts.</p>";
}

if (!$user->IsAdmin()) {
	$output = "<p>Du måste vara administratör för att visa denna sida.</p>";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Ta bort film";

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