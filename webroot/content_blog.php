<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Content WHERE type='post';");
$genres = "";
foreach ($res as $blog) {
	if ($blog->category != "") {
		$blogGenres = explode(", ", $blog->category);
	}
	foreach ($blogGenres as $currentBlogGenre) {
		$pos = strpos($genres, $currentBlogGenre);
		if ($pos === false) {
			$genres .= $currentBlogGenre . ", ";
		}
	}
}
$genres = array_filter(explode(", ", $genres));

$selectedGenres = "";
for ($i = 0; $i < sizeof($genres); $i++) {
	if (isset($_GET["genre{$i}"])) {
		$selectedGenres .= $_GET["genre{$i}"] . ", ";
	} else {
		$selectedGenres .= " , ";
	}
}
$selectedGenres = array_filter(explode(", ", $selectedGenres));

$textFilter = new CTextFilter();

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$slugSql = $slug ? 'slug = ?' : '1';

$sql = "
SELECT *
FROM kmom07_Content
WHERE
  type = 'post' AND
  $slugSql AND
  published <= NOW()
ORDER BY published DESC
;
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($slug));

$res2 = null;
$res2counter = 0;
$poscounter = 0;
for ($i = 0; $i < sizeof($res); $i++) {
	for ($j = 0; $j < sizeof($selectedGenres); $j++) {
		$pos = strpos($res[$i]->category, $selectedGenres[$j]);
		if ($pos !== false || $selectedGenres[$j] == " ") {
			$poscounter++;
		}
	}
	if ($poscounter == sizeof($selectedGenres)) {
		$res2[$res2counter++] = $res[$i];
	}
	$poscounter = 0;
}
$res = $res2;

$adminMeny = "";
if ($user->IsAuthenticated()) {
	$adminMeny = "
	<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
		<a href='".(isset($res[0])&&!isset($res[1])?"content_edit.php?id={$res[0]->id}":"content.php")."'>Uppdatera/Radera blog</a>
		<a href='content_create.php'>Skapa ny blog</a>
	</nav>
	";
	if ($slug) {
		$adminMeny = "
		<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
			<a href='".(isset($res[0])&&!isset($res[1])?"content_edit.php?id={$res[0]->id}":"content.php")."'>Uppdatera blog</a>
			<a href='".(isset($res[0])&&!isset($res[1])?"content_delete.php?id={$res[0]->id}":"content.php")."'>Radera blog</a>
			<a href='content_create.php'>Skapa ny blog</a>
		</nav>
		";
	}
}

$blog = array();
for ($i = 0; $i < sizeof($res); $i++) {
	$blog[] = new CBlog($res[$i]);
}

$output = "<h1>Nyheter</h1>";
if (!$slug) {
	for ($i = 0; $i < sizeof($res); $i++) {
		$output .= $blog[$i]->getTitle();
		$output .= substr("<p>".$blog[$i]->getText()."</p>", 0, 200);
		if (strlen($blog[$i]->getText()) > 200) {
			$output .= "...<br><br>";
			$output .= "<a href='content_blog.php?slug={$blog[$i]->getSlug()}'>Läs mer »</a>";
		}
		$output .= "<hr>";
	}
	if (sizeof($res) == 0) {
		$output .= "Det fanns inga bloggposter.";
	}
} else if (sizeof($res) == 1) {
	for ($i = 0; $i < sizeof($res); $i++) {
		$title = strip_tags($blog[$i]->getTitle());
		$output = "<nav style='font-size:14px'><a href='content_blog.php' style='text-decoration:underline'>Nyheter</a> > {$title}</nav>";
		$output .= $textFilter->doFilter($blog[$i]->getOutput(), $blog[$i]->getFilter());
	}
} else if ($slug) {
	$output .= "Det fanns inte en sådan bloggpost.";
} else {
	$output .= "Det fanns inga bloggposter.";
}
	
$genreCheckboxes = "";
for ($i = 0; $i < sizeof($genres); $i++) {
	$genreCheckboxes .= "<label class='label-checkbox'><input ".($selectedGenres[$i]!=' '?"checked":"")." type='checkbox' name='genre{$i}' value='{$genres[$i]}'>{$genres[$i]}</label>";
}

$form = "
	<h1>Kategorier</h1>
	<form>
	<p>{$genreCheckboxes}</p>
	<p style='text-align:center;'><input type='submit' name='submit' value='Visa' style='width:60px; height:30px;'/></p>
	</form>
";

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Nyheter";

$simox['main'] = <<<EOD
<div id='ContentWrapper'>
	{$adminMeny}
	<div id='content-aside' style='float:left;width:25%;'>
		{$form}
	</div>
	<div id='content-info' style='width:75%;'>
		{$output}
	</div>
</div>
EOD;

// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);