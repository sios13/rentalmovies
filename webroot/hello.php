<?php 
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');
 
// Do it and store it all in variables in the Simox container.
$simox['title'] = "Hello World";
 
$simox['main'] = <<<EOD
<h1>Hej Världen</h1>
<p>Detta är en exempelsida som visar hur Simox ser ut och fungerar.</p>
EOD;
 
 
// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);