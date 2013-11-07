<?php 
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php'); 

// Define what to include to make the plugin to work
$simox['stylesheets'][]        = 'css/slideshow.css';
$simox['javascript_include'][] = 'js/slideshow.js';


// Do it and store it all in variables in the Simox container.
$simox['title'] = "Slideshow för att testa JavaScript i Simox";

$simox['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/slideshow/" data-images='["1.jpg", "2.jpg", "3.jpg", "4.jpg"]'>
<img src='img/slideshow/1.jpg' width='950' height='180' alt='slideshow'/>
</div>

<h1>En slideshow med JavaScript</h1>
<p>Detta är en exempelsida som visar hur Simox fungerar tillsammans med JavaScript.</p>
EOD;


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);