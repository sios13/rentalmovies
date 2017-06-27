<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$content = new CContent();
$content->setDb($db);
$content->setType('insert');

$output = null;

$output .= "<h1>Skapa ny blog</h1>";
if (isset($_POST['save'])) {
	$output .= $content->execute();
}

$form = $content->makeForm();

if (!$user->IsAdmin()) {
	$output = "<p>Du måste vara administratör för att visa denna sida.</p>";
	$form = "";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Skapa ny blog";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;width:25%;'>
		{$adminAside}
	</div>
	<div id='content-info' style='width:75%;'>
		{$output}
		{$form}
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);