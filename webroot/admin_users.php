<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$hits = isset($_GET['hits']) ? $_GET['hits'] : 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$orderby = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order = isset($_GET['order'])   ? strtolower($_GET['order']) : 'asc';

$users = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users ORDER BY $orderby $order;");

$table = new CDatabaseToTable($users, array('id', 'name', 'type', 'uppdatera', 'radera'), $hits, $page, 'users');
$output = null;
$output .= "<h1>Administrera användare</h1>";
$output .= $table->getTable();

if (!$user->IsAdmin()) {
	$output = "Du måste vara administratör för att visa denna sida.";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Administrera användare";

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