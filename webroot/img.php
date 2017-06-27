<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$subdir = isset($_GET['subdir']) ? $_GET['subdir'] : null;

$CImage = new CImage($subdir);
/*
$title = $CImage->getTitle();
$output = $CImage->getOutput();
*/
// Do it and store it all in variables in the Simox container.
//$simox['title'] = "{$title}";
/*
$simox['main'] = <<<EOD
{$CImage->getOutput()}
EOD;
*/
// Finally, leave it all to the rendering phase of Simox.
//include(SIMOX_THEME_PATH);
