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

$adminMeny = "";
if ($user->IsAuthenticated()) {
	$adminMeny = "
	<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
		<a href='content_edit.php?id={$content->getId()}'>Uppdatera</a>
	</nav>
	";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Om";
$simox['main'] = "
<div id='ContentWrapper'>
	{$adminMeny}
	<div id='content-aside' style='float:left;width:25%;'>
		a
	</div>
	<div id='content-info' style='width:75%;'>
		<h1>Skapa ny blog</h1>
		<p>{$output}</p>
		{$form}
	</div>
</div>
<div id='content' style='width:95.9%'>
	<h1>Om Rental Movies</h1>
	<p>Denna webbplats är skapad av Simon Österdahl som ett projekt i kursen oophp.</p>
</div>
";
 
// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);