<?php 
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Simox container.
$simox['title'] = "404";
$simox['header'] = "";
$simox['main'] = "This is a Simox 404. Document is not here.";
$simox['footer'] = "";
 
// Send the 404 header 
header("HTTP/1.0 404 Not Found");
 
 
// Finally, leave it all to the rendering phase of Simox.
include(ANAX_THEME_PATH);