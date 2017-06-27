<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$hits = isset($_GET['hits']) ? $_GET['hits'] : 16;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$orderby = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order = isset($_GET['order'])   ? strtolower($_GET['order']) : 'asc';

/**
 * Create a link to the content, based on its type.
 *
 * @param object $content to link to.
 * @return string with url to display content.
 */
function getUrlToContent($content) {
  switch($content->type) {
    case 'page': return "content_page.php?url={$content->url}"; break;
    case 'post': return "content_blog.php?slug={$content->slug}"; break;
    default: return null; break;
  }
}

$sql = "SELECT *, (published <= NOW()) AS available FROM kmom07_Content WHERE type = 'post' ORDER BY $orderby $order;";
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

$table = new CDatabaseToTable($res, array('id', 'title', 'published', 'uppdatera', 'radera'), $hits, $page, 'news');

$output = null;
$output .= "<h1>Uppdatera/Radera nyhet</h1>";
$output .= $table->getTable();

if (!$user->IsAdmin()) {
	$output = "<p>Du måste vara administratör för att visa denna sida.</p>";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Content";

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