<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

$sql = "
	SELECT *
	FROM kmom07_Content
	WHERE
	  type = 'page' AND
	  url = ? AND
	  published <= NOW();
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($url));

$adminMeny = "";
$output = null;
if (sizeof($res) == 1) {
	if ($user->IsAuthenticated()) {
		$adminMeny = "
		<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
			<a href='content_edit.php?id={$res[0]->id}'>Uppdatera</a>
		</nav>
		";
	}

	$textFilter = new CTextFilter();

	$page = new CPage($res[0]);

	$title = $page->getTitle();
	$output = $textFilter->doFilter(htmlentities($res[0]->data, null, 'UTF-8'), $page->getFilter());
} else {
	$output = "<p>Ingen sida har angetts.</p>";
}
// Do it and store it all in variables in the Simox container.
$simox['title'] = isset($title) ? $title : "Innehåll";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	{$adminMeny}
	<div id='content-aside' style='width:25%;'>
	</div>
	<div id='content-info' style='width:75%;'>
		{$output}
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);