<?php 
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');
 
// Do it and store it all in variables in the Simox container.
$simox['title'] = "Nyheter";
$simox['main'] = "
<div id='ContentWrapper'>
	<div id='content-aside' style='float:left;'>
		<h1>Kategorier</h1>
	</div>
	<div id='content-info'>
		<h1>Nyheter</h1>
	</div>
</div>
";
 
// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);