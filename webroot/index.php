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

$latestMovies = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies ORDER BY id DESC LIMIT 10");
$latestMoviesStr = "<nav class='imglinks'>";
foreach ($latestMovies as $movie) {
	$latestMoviesStr .= "<a class='imglinks' href='movies.php?id={$movie->id}'><img src='img.php?src={$movie->image}&amp;subdir=movies&amp;width=130&amp;height=200&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/></a>";
}
$latestMoviesStr .= "</nav>";

$actionMovies = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies WHERE category like '%action%' ORDER BY RAND() DESC LIMIT 5");
$actionMoviesStr = "<nav class='imglinks'>'";
foreach ($actionMovies as $movie) {
	$actionMoviesStr .= "<a class='imglinks' href='movies.php?id={$movie->id}'><img src='img.php?src={$movie->image}&amp;subdir=movies&amp;width=130&amp;height=200&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/></a>";
}
$actionMoviesStr .= "</nav>";

$fantasyMovies = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies WHERE category like '%fantasy%' ORDER BY RAND() DESC LIMIT 5");
$fantasyMoviesStr = "<nav class='imglinks'>'";
foreach ($fantasyMovies as $movie) {
	$fantasyMoviesStr .= "<a class='imglinks' href='movies.php?id={$movie->id}'><img src='img.php?src={$movie->image}&amp;subdir=movies&amp;width=130&amp;height=200&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/></a>";
}
$fantasyMoviesStr .= "</nav>";

$scifiMovies = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies WHERE category like '%sci-fi%' ORDER BY RAND() DESC LIMIT 5");
$scifiMoviesStr = "<nav class='imglinks'>'";
foreach ($scifiMovies as $movie) {
	$scifiMoviesStr .= "<a class='imglinks' href='movies.php?id={$movie->id}'><img src='img.php?src={$movie->image}&amp;subdir=movies&amp;width=130&amp;height=200&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/></a>";
}
$scifiMoviesStr .= "</nav>";

$familjMovies = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies WHERE category like '%familj%' ORDER BY RAND() DESC LIMIT 5");
$familjMoviesStr = "<nav class='imglinks'>'";
foreach ($familjMovies as $movie) {
	$familjMoviesStr .= "<a class='imglinks' href='movies.php?id={$movie->id}'><img src='img.php?src={$movie->image}&amp;subdir=movies&amp;width=130&amp;height=200&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/></a>";
}
$familjMoviesStr .= "</nav>";

$news = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Content WHERE type='post' ORDER BY published ASC LIMIT 5");
$newsStr = "";
for ($i = 0; $i < sizeof($news); $i++) {
	$newsStr .= "<h3 style='padding-left:20px;'><a href='content_blog.php?slug={$news[$i]->slug}'>{$news[$i]->title}</a></h3>";
	$newsStr .= "<p style='font-size:14px;font-style:italic;padding-left:22px;padding-bottom:15px;'>{$news[$i]->published}</p>";
}

$slideshowImages = array();
for ($i = 0; $i < 5; $i++) {
	$slideshowImages[$i] = "-.-" . str_replace(array(".jpg", ".png"), "", $latestMovies[$i]->image_header) . "_980_320_q60_cf_s.jpg";
}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "RM Rental Movies";

$simox['main'] = <<<EOD
<div id="slideshow" class='slideshow' data-host="" data-path="img/cache/" data-images='["{$slideshowImages[1]}", "{$slideshowImages[2]}", "{$slideshowImages[3]}", "{$slideshowImages[4]}"]'>
	<img src='img.php?src={$latestMovies[0]->image_header}&amp;subdir=movies&amp;width=980&amp;height=320&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' height='320' alt='{$latestMovies[0]->title}' />
</div>
<div id='ContentWrapper'>
	<div id='content-info'>
		<h1><a href='movies.php?p=movies'>Senaste filmerna</a></h1>
		{$latestMoviesStr}
		<h1 style='padding-top:20px'><a href='movies.php?genre0=action'>Action</a></h1>
		{$actionMoviesStr}
		<h1 style='padding-top:20px'><a href='movies.php?genre7=familj'>Familj</a></h1>
		{$familjMoviesStr}
		<h1 style='padding-top:20px'><a href='movies.php?genre5=fantasy'>Fantasy</a></h1>
		{$fantasyMoviesStr}
		<h1 style='padding-top:20px'><a href='movies.php?genre3=sci-fi'>Sci-Fi</a></h1>
		{$scifiMoviesStr}
	</div>
	<div id='content-aside'>
		<h1 style='padding-left:20px;'>Nyheter</h1>
		{$newsStr}
	</div>
</div>
EOD;


// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);