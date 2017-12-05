<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php
$pagetitle = "Home";

include "header.php";

$recipebesthelp = $dbh -> prepare("SELECT * FROM recipes ORDER BY rating desc LIMIT 9;");
$recipebesthelp->execute();
$recipebest = $recipebesthelp->fetchAll();
$recipenewhelp = $dbh -> prepare("SELECT * FROM recipes ORDER BY time desc LIMIT 9;");
$recipenewhelp->execute();
$recipenew = $recipenewhelp->fetchAll();
?>
<script>
	$(document).ready(function() {
		//Checken wie viele Zeilen es gibt an Bildern und die div drumherum von der Größe anpassen, damit der Inhalt unter der div ideal dargestellt wird 
		var width = $(window).width();

		var lengthindex = $('.index .left').size();

		if (lengthindex > 0 && width > 1272) {
			$('.dreieraufteilung').css('height', (450 * lengthindex / 2));

		} else if (lengthindex > 0 && width > 750) {
			$('.dreieraufteilung').css('height', (300 * lengthindex / 2));
			$('.dreieraufteilung div').css('height', (280));
		}
	}); 
</script>

<div class="container index">
	<h1> Latest recipes </h1>
	<div class="dreieraufteilung">
		<?php $counter = 1;
		foreach ($recipenew as $recipe) {
			//Algorithmus, damit die divs die richtige Class bekommen
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
		//Falls noch Platz vorhanden diesen mit leeren divs füllen
		if (($counter) % 3 == 0)
			echo "<div class='right'> </div>";
		else if (($counter + 1) % 3 == 0)
			echo "<div class='middle'> </div> <div class='right'> </div>";
		echo "<span class='break'></span> ";
		?>
	</div>
    
    <!-- Gleicher Code wie oben nur mit den meist bewerteten Rezepten --> 
	<h1> Most rated recipes </h1>
	<div class="dreieraufteilung">
		<?php $counter = 1;
		foreach ($recipebest as $recipe) {
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
