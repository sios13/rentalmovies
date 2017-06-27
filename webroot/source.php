<?php 
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');
 
// Define what to include to make the plugin to work
$simox['stylesheets'][] = 'css/source.css';
 
// Create the object to display sourcecode
//$source = new CSource();
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));
 
// Do it and store it all in variables in the Simox container.
$simox['title'] = "Källkod";
$simox['main'] = "
<div id='ContentWrapper'>
	<div id='content-info'>
		<h1>Källkod</h1>
		{$source->View()}
	</div>
	<div id='content-aside'>
	</div>
</div>
<div id='content' style='width:95.9%'>
</div>
";
 
// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);