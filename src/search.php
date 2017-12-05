<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php
$pagetitle = "Search";

include "header.php";
?>

<script>
	$(document).ready(function() {
		var width = $(window).width();
		var lengthsearch = $('.search .left').size();

		if (lengthsearch > 0 && width > 1272) {
			$('.dreieraufteilung').css('height', (450 * lengthsearch));

		} else if (lengthsearch > 0 && width > 750) {
			$('.dreieraufteilung').css('height', (300 * lengthsearch));
			$('.dreieraufteilung div').css('height', (280));
		}

		$('html, body').animate({
			scrollTop : $("#result").offset().top
		}, 2000);
	});

</script>
<div class="container search">
	<h1> search </h1>

	<div class="formular">
		<form class="recipe" method="GET" action="">
			<h2> SEARCH FOR A CERTAIN KEYWORD </h2>
			<input class="input" type ="text" name="keyword" maxlength="30" />
			<h2> Search for category </h2>
			<input type='checkbox' name='appetizer'>
			appetizer
			<input type='checkbox' name='maindish'>
			main dish
			<br />
			<input type='checkbox' name='dessert'>
			dessert
			<input type='checkbox' name='drink'>
			drink
			<br>
			<h2> Search for difficulty </h2>
			<input type='checkbox' name='easy'>
			easy
			<input type='checkbox' name='medium'>
			medium
			<input type='checkbox' name='hard' >
			hard
			<br>
			<h2> Search for maximum preparationstime in minutes: </h2>
			<input class="numberinput" type="number" min="0" max="500" step="5" value="0" name="preptime" >
			<h2> search for additional information: </h2>
			<input type="checkbox" name="lactosefree">
			lactosefree
			<input type="checkbox" name="glutenfree">
			glutenfree
			<br />
			<input type="checkbox" name="vegan">
			vegan
			<input type="checkbox" name="vegeterian">
			vegetarien
			<br />
			<input class="button submit" type="submit" value="search" name="search" >
		</form>
	</div>
	<?php

	if (isset ($_GET['search'])):

	//Query für die Abfrage zusammenbauen

	$query = " ";
	$keywordinput = strip_tags($_GET['keyword']);
	if ($keywordinput != null) {
		$keyword = "%".preg_replace("/[^a-zA-ZäöüÄÖÜß ]/", "",$keywordinput )."%";
		$query = $query . "(title like '". $keyword . "')";
	}
	if (isset( $_GET['appetizer'])) {
	$appetizer = true;
	}
	else $appetizer = false;
	if (isset( $_GET['maindish'])) {
	$maindish = true;
	}
	else $maindish = false;

	if (isset( $_GET['dessert'])) {
	$dessert = true;
	}
	else $dessert = false;

	if (isset( $_GET['drink'])) $drink = true;
	else $drink = false;

	if ($appetizer or $maindish or $dessert or $drink){
	if ($query != " " ) $query = $query . " AND (";
	else $query = $query . "(";
	if ($appetizer) $query .= "category = 'appetizer'";
	if ($appetizer && $maindish) $query .= " OR category = 'maindish'";
	else if ($maindish) $query .= "category = 'maindish'";
	if (($dessert && $appetizer) or ($dessert && $maindish)) $query .= " OR category = 'dessert'";
	else if ($dessert) $query .= "category = 'dessert'";
	if (($drink && $appetizer) or ($drink && $maindish) or ($drink && $dessert)) $query .= " OR category = 'drink'";
	else if ($drink) $query .= "category = 'drink'";
	$query = $query . ")";
	}

	//DIFFICULTY

	if (isset( $_GET['easy'])) {
	$easy = true;
	}
	else $easy = false;
	if (isset( $_GET['medium'])) {
	$medium = true;
	}
	else $medium = false;
	if (isset( $_GET['hard'])) {
	$hard = true;
	}
	else $hard = false;

	if (($easy && $medium) or ($medium && $hard) or ($easy && $hard)){ //Wenn mehr als eines aktiviert -> OR
	if ($query != " " ) $query = $query . " AND (";
	else  $query = $query . "(";
	if ($easy) $query = $query . "difficulty = 'easy'";
	if ($easy && $medium) $query = $query . " OR difficulty = 'medium'";
	else if ($medium) $query = $query . "difficulty = 'medium'";
	if ($hard) $query = $query . " OR difficulty = 'hard'";
	$query = $query . ")";
	}
	else if ($easy) {
	if ($query != " " ) $query = $query . "AND difficulty = 'easy'";
	else $query = $query . "difficulty = 'easy'";
	}
	else if ($medium) {
	if ($query != " " ) $query = $query . "AND difficulty = 'medium'";
	else $query = $query . "difficulty = 'medium'";
	}
	else if ($hard) {
	if ($query != " " ) $query = $query . "AND difficulty = 'hard'";
	else $query = $query . "difficulty = 'hard'";
	}

	//PREPTIME
	$preptime = $_GET['preptime'];
	if ($preptime > 0 && $query =! " " ){
	$query = $query . " AND (preptime < $preptime)";
	}
	else if ($preptime > 0) $query = $query . " (preptime <= $preptime)";

	if (isset( $_GET['lactosefree'])) {
	if ($query != " ") $query.= "AND (lactosefree = true)";
	else $query.= "(lactosefree = true)";
	}
	if (isset( $_GET['glutenfree'])) {
	if ($query != " ") $query.= "AND ( glutenfree = true)";
	else $query.= "(glutenfree = true)";
	}
	if (isset( $_GET['vegan']))	{
	if ($query != " ") $query.= "AND ( vegan = true)";
	else $query.= "(vegan = true)";
	}
	if (isset( $_GET['vegeterian'])){
	if ($query != " ") $query.= "AND ( vegetarian = true)";
	else $query.= "(vegetarian = true)";
	}

	if ($query == " ") $query = "1=1";
	$searchresulthelp = $dbh->prepare("SELECT title, id FROM recipes WHERE $query ;");
	$searchresulthelp->execute();
	$searchresult = $searchresulthelp->fetchAll();
	?>
	<h1 id="result"> Results </h1>
	<?php
	if ($searchresult == null)
		echo "<h2 class='error center'> Sorry, there are no suitable recipes! </h2>";
	else {
		echo "<div class='dreieraufteilung'>";
		$counter = 1;
		foreach ($searchresult as $recipe) {
			//Algorithmus damit jede div die richtige Class bekommt
			if (($counter + 2) % 3 == 0)
				$annordnung = "left";
			else if (($counter + 1) % 3 == 0)
				$annordnung = "middle";
			else
				$annordnung = "right";

			echo "<div class='" . $annordnung . "'> <a href='recipe.php?id=$recipe->id'> <img src='imgrecipe/" . $recipe -> id . ".jpg' title='" . $recipe -> title . "' alt='dish'> </a> <br> <a href='recipe.php?id=$recipe->id'> <h2 class='center'> $recipe->title </h2></a> </div>";
			if ($counter % 3 == 0)
				echo "<span class='break'></span> ";
			$counter = $counter + 1;
		}
		// Falls dann rechts noch Platz ist, wird dieser mit divs ausgefüllt
		if (($counter) % 3 == 0)
			echo "<div class='right'> </div>";
		else if (($counter + 1) % 3 == 0)
			echo "<div class='middle'> </div> <div class='right'> </div>";
		echo "<span class='break'></span> ";
		echo "</div> ";
	}
	endif;
?>
</div>

<?php
include "footer.php";
?>