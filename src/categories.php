<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<?php
$pagetitle = "Categories";

include "header.php";

$appetizerhelp = $dbh -> prepare("SELECT * FROM recipes WHERE category = 'appetizer' ORDER BY RANDOM() LIMIT 6");
$appetizerhelp->execute();
$appetizer = $appetizerhelp->fetchAll();

$maindishhelp = $dbh -> prepare("SELECT * FROM recipes WHERE category = 'maindish' ORDER BY RANDOM() LIMIT 6");
$maindishhelp->execute();
$maindish = $maindishhelp->fetchAll();

$desserthelp = $dbh -> prepare("SELECT * FROM recipes WHERE category = 'dessert' ORDER BY RANDOM() LIMIT 6");
$desserthelp->execute();
$dessert = $desserthelp->fetchAll();

$drinkhelp = $dbh -> prepare("SELECT * FROM recipes WHERE category = 'drink' ORDER BY RANDOM() LIMIT 6");
$drinkhelp->execute();
$drink = $drinkhelp->fetchAll();
?>
<script>
	$(document).ready(function() {
		
		//Hier wird geprüft wie viele Reihen es für die jeweilige Kategorie gibt und dann die div, die über diese Kategorie geht auf die richtige Größe angepasst
		var width = $(window).width();

		var lengthapp = $('.appetizer .left').size();

		if (lengthapp > 0 && width > 1272) {
			$('.appetizer').css('height', (450 * lengthapp));

		} else if (lengthapp > 0 && width > 750) {
			$('.appetizer').css('height', (300 * lengthapp));
			$('.appetizer div').css('height', (280));
		}

		var lengthmain = $('.main .left').size();

		if (lengthmain > 0 && width > 1272) {
			$('.main').css('height', (450 * lengthmain));

		} else if (lengthmain > 0 && width > 750) {
			$('.main').css('height', (300 * lengthmain));
			$('.main div').css('height', (280));
		}

		var lengthdessert = $('.dessert .left').size();

		if (lengthdessert > 0 && width > 1272) {
			$('.dessert').css('height', (450 * lengthdessert));

		} else if (lengthdessert > 0 && width > 750) {
			$('.dessert').css('height', (300 * lengthdessert));
			$('.dessert div').css('height', (280));
		}

		var lengthdrink = $('.drink .left').size();

		if (lengthdrink > 0 && width > 1272) {
			$('.drink').css('height', (450 * lengthdrink));

		} else if (lengthdrink > 0 && width > 750) {
			$('.drink').css('height', (300 * lengthdrink));
			$('.drink div').css('height', (280));
		}
	}); 
</script>
<div class="container">
	<h1> appetizer </h1>

	<div class="dreieraufteilung appetizer">
		<?php $counter = 1;
		foreach ($appetizer as $recipe) {
			//Algorithmus damit die divs die richtige Clas bekommen und richtig dargestellt werden: 
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
		//Wenn noch rechts Platz übrig ist, dann wird dieser mit divs ausgefüllt
		if (($counter) % 3 == 0)
			echo "<div class='right'> </div>";
		else if (($counter + 1) % 3 == 0)
			echo "<div class='middle'> </div> <div class='right'> </div>";
		echo "<span class='break'></span> ";
		?>
	</div>
    
    <!-- Gleiches Prinzip wie bei der oberen Kategorie auch bei den folgenden --> 

	<h1> main dish </h1>

	<div class="dreieraufteilung main">
		<?php $counter = 1;
		foreach ($maindish as $recipe) {
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

		if (($counter) % 3 == 0)
			echo "<div class='right'> </div>";
		else if (($counter + 1) % 3 == 0)
			echo "<div class='middle'> </div> <div class='right'> </div>";
		echo "<span class='break'></span> ";
		?>
	</div>

	<h1> dessert </h1>

	<div class="dreieraufteilung dessert">
		<?php $counter = 1;
		foreach ($dessert as $recipe) {
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

		if (($counter) % 3 == 0)
			echo "<div class='right'> </div>";
		else if (($counter + 1) % 3 == 0)
			echo "<div class='middle'> </div> <div class='right'> </div>";
		echo "<span class='break'></span> ";
		?>
	</div>

	<h1> drink </h1>
	<div class="dreieraufteilung drink">
		<?php $counter = 1;
		foreach ($drink as $recipe) {
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

		if (($counter) % 3 == 0)
			echo "<div class='right'> </div>";
		else if (($counter + 1) % 3 == 0)
			echo "<div class='middle'> </div> <div class='right'> </div>";
		echo "<span class='break'></span> ";
		?>
	</div>
</div>

<?php
include "footer.php";
?>