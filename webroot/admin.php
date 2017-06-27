<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$output = "<p>Välj något till vänster att administrera.</p>";

if (!$user->IsAdmin()) {
	$output = "<p>Du måste vara administratör för att visa denna sida.</p>";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Admin";

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