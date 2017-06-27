<?php
/**
 * This is a Simox pagecontroller.
 *
 */
// Include the essential config-file which also creates the $simox variable with its defaults.
include(__DIR__.'/config.php');

$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies;");
$genres = "";
foreach ($res as $movie) {
	if ($movie->category != "") {
		$movieGenres = explode(", ", $movie->category);
	}
	foreach ($movieGenres as $currentMovieGenre) {
		$pos = strpos($genres, $currentMovieGenre);
		if ($pos === false) {
			$genres .= $currentMovieGenre . ", ";
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

$output = null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$adminMeny = "";
if ($user->IsAuthenticated()) {
	$adminMeny = "
	<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
		<a href='movies_edit.php".(isset($id)?"?id={$id}":"")."'>Uppdatera/Radera film</a>
		<a href='movies_create.php'>Lägg till film</a>
	</nav>
	";
	if ($id) {
		$adminMeny = "
		<nav style='text-align:center;padding:10px;margin:10px;background-color:yellow;border:3px solid red'>
			<a href='movies_edit.php".(isset($id)?"?id={$id}":"")."'>Uppdatera film</a>
			<a href='movies_delete.php".(isset($id)?"?id={$id}":"")."'>Radera film</a>
			<a href='movies_create.php'>Lägg till film</a>
		</nav>
		";
	}
}

if (!isset($id)) {

	$hits = isset($_GET['hits']) ? $_GET['hits'] : 8;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	$year1 = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
	$year2 = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
	$title = isset($_GET['title']) ? $_GET['title'] : null;
	$orderby = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'title';
	$order = isset($_GET['order'])   ? strtolower($_GET['order']) : 'asc';

	in_array($orderby, array('image', 'title', 'year', 'category', '')) or die('Check: Not valid column.');
	in_array($order, array('asc', 'desc', '')) or die('Check: Not valid sort order.');

	$sql = null;
	if ($title && $year1 && $year2) {
		$sql = "SELECT * FROM kmom07_Movies WHERE title LIKE ? AND year >= ? AND year <= ?";
		$params = array(
			'%' . $title . '%',
			$year1,
			$year2,
		);
	} else if ($title && $year1) {
		$sql = "SELECT * FROM kmom07_Movies WHERE title LIKE ? AND year >= ?";
		$params = array(
			'%' . $title . '%',
			$year1,
		);
	} else if ($title && $year2) {
		$sql = "SELECT * FROM kmom07_Movies WHERE title LIKE ? AND year <= ?";
		$params = array(
			'%' . $title . '%',
			$year2,
		);
	} else if ($title) {
		$sql = "SELECT * FROM kmom07_Movies WHERE title LIKE ?";
		$params = array(
			'%' . $title . '%',
		);
	} else if ($year1 && $year2) {
		$sql = "SELECT * FROM kmom07_Movies WHERE year >= ? AND year <= ?";
		$params = array(
			$year1,
			$year2,
		);
	} else if ($year1) {
		$sql = "SELECT * FROM kmom07_Movies WHERE year >= ?";
		$params = array(
			$year1,
		);
	} else if ($year2) {
		$sql = "SELECT * FROM kmom07_Movies WHERE year <= ?";
		$params = array(
			$year2,
		);
	} else if ($orderby && $order) {
		$sql = "SELECT * FROM kmom07_Movies";
		$params = null;
	} else {
		$sql = "SELECT * FROM kmom07_Movies";
		$params = null;
	}
	$sql .= " ORDER BY $orderby $order;";
	$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
	
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
	
	$table = null;
	$noresults = sizeof($res) > 0 ? false : true;
	if (!$noresults) {
		$table = new CDatabaseToTable($res, array('image', 'title', 'year', 'category'), $hits, $page, 'movies');
		$table = $table->getTable();
	} else {
		$table = "<p>Inga filmer hittades.</p>";
	}
	
	$genreCheckboxes = "";
	for ($i = 0; $i < sizeof($genres); $i++) {
		$genreCheckboxes .= "<label class='label-checkbox'><input ".($selectedGenres[$i]!=' '?"checked":"")." type='checkbox' name='genre{$i}' value='{$genres[$i]}'>{$genres[$i]}</label>";
	}
	
	$form = "<form>
		<fieldset>
		<legend>Sök</legend>
		<p><label>Titel
			<input type='search' name='title' value='{$title}' />
			</label>
		</p>
		<p><label>År
			<input type='text' name='year1' size='4' value='{$year1}' /></label>
			-
			<label><input type='text' name='year2' size='4' value='{$year2}' /></label>
		</p>
		<p>Genre
			{$genreCheckboxes}
		</p>
		<p style='text-align:center;'><input type='submit' name='submit' value='Visa' style='width:60px; height:30px;'/></p>
		</fieldset>
		</form>";

	$output = <<<EOD
	<div id='ContentWrapper'>
			{$adminMeny}
		<div id='content-aside' style='width:25%;float:left;'>
			{$form}
		</div>
		<div id='content-info' style='width:75%;'>
			{$table}
		</div>
	</div>
EOD;

} else {

	$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies WHERE id = ?", array($id));
	$movie = $res[0];

	if (isset($_GET['rent'])) {
		$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies WHERE title='{$movie->title}' LIMIT 1;");
		$movieTitle = $res[0]->title;
		$res = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Users WHERE name = ?;", array($user->getName()));
		$userMovies = $res[0]->movies;
		$userMovies .= "." . $movieTitle;
		$db->ExecuteQuery("UPDATE kmom07_Users SET movies = ? WHERE name = ?;", array($userMovies, $user->getName()));
	}
	
	if ($user->isAuthenticated()) {
		$rentbox = "<div style='width:333px;height:66px;float:left;'><a style='background-color:transparent;background:transparent;' href='?id={$movie->id}&amp;rent'><div id='content-info-rentbox'>HYR {$movie->price} kr</div></a></div>";
	} else {
		$rentbox = "<div style='width:333px;height:66px;float:left;'><div id='content-info-rentbox' style='width:250px;margin-left:40px;background-color:grey;'>Logga in för att hyra denna film</div></div>";
	}
	
	$relatedMovies = $db->ExecuteSelectQueryAndFetchAll("SELECT * FROM kmom07_Movies ORDER BY RAND() LIMIT 6");
	$relatedMoviesStr = "<nav class='imglinks' style='clear:both;'>";
	for ($i = 0; $i < 6; $i++) {
		$relatedMoviesStr .= "<a class='imglinks' href='movies.php?id={$relatedMovies[$i]->id}'><img src='img.php?src={$relatedMovies[$i]->image}&amp;subdir=movies&amp;width=150&amp;height=222&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$relatedMovies[$i]->title}'/></a>";
	}
	$relatedMoviesStr .= "</nav>";
	
	$output = <<<EOD
	<img src='img.php?src={$movie->image_header}&amp;subdir=movies&amp;width=980&amp;height=320&amp;crop-to-fit&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/>
	<div id='ContentWrapper'>
		{$adminMeny}
		<div id='content-info'>
			<nav style='font-size:14px'><a href='movies.php' style='text-decoration:underline'>Filmer</a> > {$movie->title}</nav>
			<h1>{$movie->title}</h1>
			<img src='img.php?src={$movie->image}&amp;subdir=movies&amp;width=200&amp;sharpen&amp;save-as=jpg' alt='{$movie->title}'/>
			<div id='content-info-starbox'>{$movie->score}</div>
			{$rentbox}
			<p style='padding:80px 20px 0px 210px;'>{$movie->text}</p>
		</div>
		<div id='content-aside'>
			<h1>Filmfakta</h1>
			<p>Genre: {$movie->category}</p>
			<p>Regissör: {$movie->director}</p>
			<p>År: {$movie->year}</p>
			<p>Speltid: {$movie->length} min</p>
			<p>Språk: {$movie->speech}</p>
			<p>Undertext: {$movie->subtext}</p>
			<p>Pris: {$movie->price} kr</p>
			<p><a href='http://www.imdb.com/title/{$movie->imdb}/'>IMDB</a></p>
		</div>
		<div id='content-trailer'>
			<h1 style='float:left;'>Trailer</h1>
			<iframe width="640" height="360" src="//www.youtube.com/embed/{$movie->youtube}" allowfullscreen></iframe>
		</div>
		<div id='content-relatedmovies'>
			<h1 style='float:left;'>Relaterade filmer</h1>
			{$relatedMoviesStr}
		</div>
	</div>
EOD;

}

// Do it and store it all in variables in the Simox container.
$simox['title'] = "Filmer";
 
$simox['main'] = <<<EOD
	{$output}
EOD;
// Finally, leave it all to the rendering phase of Simox.
include(SIMOX_THEME_PATH);