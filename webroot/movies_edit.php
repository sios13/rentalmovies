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

$output = null;

$id           = isset($_POST['id'])           ? strip_tags($_POST['id'])           : null;
$title        = isset($_POST['title'])        ? strip_tags($_POST['title'])        : null;
$text         = isset($_POST['text'])         ? strip_tags($_POST['text'])         : null;
$category     = isset($_POST['category'])     ? strip_tags($_POST['category'])     : null;
$director     = isset($_POST['director'])     ? strip_tags($_POST['director'])     : null;
$length       = isset($_POST['length'])       ? strip_tags($_POST['length'])       : null;
$year         = isset($_POST['year'])         ? strip_tags($_POST['year'])         : null;
$subtext      = isset($_POST['subtext'])      ? strip_tags($_POST['subtext'])      : null;
$speech       = isset($_POST['speech'])       ? strip_tags($_POST['speech'])       : null;
$price        = isset($_POST['price'])        ? strip_tags($_POST['price'])        : null;
$image        = isset($_POST['image'])        ? strip_tags($_POST['image'])        : null;
$image_header = isset($_POST['image_header']) ? strip_tags($_POST['image_header']) : null;
$youtube      = isset($_POST['youtube'])      ? strip_tags($_POST['youtube'])      : null;
$imdb         = isset($_POST['imdb'])         ? strip_tags($_POST['imdb'])         : null;
$score        = isset($_POST['score'])        ? strip_tags($_POST['score'])        : null;
$published    = isset($_POST['published'])    ? strip_tags($_POST['published'])    : null;
$save         = isset($_POST['save'])         ? true : false;

if ($save) {
	$sql = '
		UPDATE kmom07_Movies SET
		title = ?,
		text = ?,
		category = ?,
		director = ?,
		length = ?,
		year = ?,
		subtext = ?,
		speech = ?,
		price = ?,
		image = ?,
		image_header = ?,
		youtube = ?,
		imdb = ?,
		score = ?,
		published = ?,
		updated = NOW()
		WHERE
		id = ?
	';
	$params = array($title, $text, $category, $director, $length, $year, $subtext, $speech, $price, $image, $image_header, $youtube, $imdb, $score, $published, $id);
	$db->ExecuteQuery($sql, $params);
	$output .= 'Informationen sparades.';
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	is_numeric($id) or die('Check: Id must be numeric.');
	
	$sql = "SELECT * FROM kmom07_Movies WHERE id=?";
	$params = array($id);
	$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
	
	if(isset($res[0])) {
		$movie = $res[0];
	}
	else {
		die('Failed: There is no movie with that id');
	}
	
	$output .= "
	<form method=post>
		<fieldset>
		<legend>Uppdatera film</legend>
		<input type='hidden' name='id' value='{$id}' />
		<p><label>Titel:<br/><input type='text' name='title' value='{$movie->title}'></label></p>
		<p><label>Text:<br/><textarea name='text' rows='10' cols='50'>{$movie->text}</textarea></label></p>
		<p><label>Genre:<br/><input type='text' name='category' value='{$movie->category}'></label></p>
		<p><label>Regissör:<br/><input type='text' name='director' value='{$movie->director}'></label></p>
		<p><label>Tid:<br/><input type='text' name='length' value='{$movie->length}'></label></p>
		<p><label>År:<br/><input type='text' name='year' value='{$movie->year}'></label></p>
		<p><label>Undertext:<br/><input type='text' name='subtext' value='{$movie->subtext}'></label></p>
		<p><label>Språk:<br/><input type='text' name='speech' value='{$movie->speech}'></label></p>
		<p><label>Pris:<br/><input type='text' name='price' value='{$movie->price}'></label></p>
		<p><label>Bild (URL):<br/><input type='text' name='image' value='{$movie->image}'></label></p>
		<p><label>Bild header (URL):<br/><input type='text' name='image_header' value='{$movie->image_header}'></label></p>
		<p><label>Youtube:<br/><input type='text' name='youtube' value='{$movie->youtube}'></label></p>
		<p><label>IMDB:<br/><input type='text' name='imdb' value='{$movie->imdb}'></label></p>
		<p><label>Poäng:<br/><input type='text' name='score' value='{$movie->score}'></label></p>
		<p><label>Publiceras:<br/><input type='text' name='pubilshed' value='{$movie->published}'></label></p>
		<p><input type='submit' name='save' value='Spara'/> <input type='reset' value='Återställ'/></p>
		</fieldset>
	</form>
	";
} else {
	$sql = "SELECT * FROM kmom07_Movies;";
	$res = $db->ExecuteSelectQueryAndFetchAll($sql);
	
	$table = new CDatabaseToTable($res, array('id', 'title', 'published', 'uppdatera', 'radera'), $hits, $page, 'moviesedit');

	$rowsInDB = sizeof($res);
	$output .= "<h1>Uppdatera film</h1>";
	$output .= $table->getTable();
}

if (!$user->IsAdmin()) {
	$output = "<p>Du måste vara administratör för att visa denna sida.</p>";
	$adminAside = "";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Uppdatera filmdatabas";

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