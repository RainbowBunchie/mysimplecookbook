<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php
$id = $_GET['id'];

$pagetitle = "Profile";

include "header.php";

$commentcounthelp = $dbh -> prepare("SELECT count(*) AS c FROM comments WHERE id_user = '$id';");
$commentcounthelp->execute();
$commentcount = $commentcounthelp->fetch();
$favecounthelp = $dbh -> prepare("select count(*) c from recipes, favorites where recipes.id = favorites.id_recipe and id_user = $id");
$favecounthelp->execute();
$favecount = $favecounthelp->fetch();
$sharecounthelp = $dbh -> prepare("SELECT count(*) AS c FROM recipes WHERE shared_by = '$id';");
$sharecounthelp->execute();
$sharecount = $sharecounthelp -> fetch();
$ratingcounthelp = $dbh -> prepare("SELECT count(*) c FROM ratings WHERE id_user = $id;");
$ratingcounthelp->execute();
$ratingcount = $ratingcounthelp->fetch();

$userhelp = $dbh -> prepare("SELECT username, firstname, isfemale FROM users WHERE id='$id';");
$userhelp->execute();
$user = $userhelp->fetch();
$sharedhelp = $dbh -> prepare("SELECT * FROM recipes WHERE shared_by='$id';");
$sharedhelp->execute();
$shared = $sharedhelp->fetchAll();
$faveshelp = $dbh -> prepare("select * from recipes, favorites where recipes.id = favorites.id_recipe and id_user = $id;");
$faveshelp->execute();
$faves = $faveshelp->fetchAll();

if (isset($_SESSION['loggedin']) and $id == $_SESSION['ID'])
	$sameperson = true;
else
	$sameperson = false;
?>

<script>
	$(document).ready(function() {
		var width = $(window).width();
		
		//Checken wie viele Zeilen es gibt an Bildern und die div drumherum von der Größe anpassen, damit der Inhalt unter der div ideal dargestellt wird 

		var lengthshare = $('.share .left').size();
		var lengthfave = $('.fave .left').size();

		if (lengthshare > 0 && width > 1272) {
			$('.share').css('height', (450 * lengthshare));

		} else if (lengthshare > 0 && width > 750) {
			$('.share').css('height', (300 * lengthshare));
			$('.share div').css('height', (280));
		}
		if (lengthfave > 0 && width > 1272) {
			$('.fave').css('height', (450 * lengthfave));

		} else if (lengthfave > 0 && width > 750) {
			$('.fave').css('height', (300 * lengthfave));
			$('.fave div').css('height', (280));
		}
	}); 
</script>			

<div class="container profile">
	<?php 
	//Prüfen ob User eingeloggt ist 
	if (!isset($_SESSION['loggedin'])):	?> 
        <h2 class="center error"> You must be logged in to view a user's profile </h2>      
    <?php
			else:
			if ($sameperson) echo "<h1> your profile (<a href='logout.php'> log out </a>/<a href='edit-profile.php?id=$id'> edit </a>)</h1>";
			else echo "<h1>  $user->username's profile </h1>";
    ?>		
    	<div class="dreieraufteilung">
        	<div class="image left"> 
            	<?php
				//Profilbild anzeigen
				if (file_exists("imgprofile/" . $id . ".jpg")) {
					echo "<img src='imgprofile/" . $id . ".jpg' title='profile picture' alt='profile picture'>";
				} else {
					if ($user -> isfemale == true)
						echo "<img src='img/user-female.png' title='profile picture' alt='profile picture'>";
					else
						echo "<img src='img/user-male.png' title='profile picture' alt='profile picture'>";
				}
					?>
            </div> 
            <!-- Stats des Users anzeigen -->
            <div> 
            	<h2> <?php echo $user->username ?>'s stats </h2>
                <ul id="profilelist"> 
                	<li> Shared recipes: <?php echo $sharecount->c ?></li>
                    <li> Saved recipes:  <?php echo $favecount->c ?> </li>
                    <li> Comments posted: <?php echo $commentcount->c ?> </li>
                     <li> Recipes rated: <?php echo $ratingcount->c ?> </li>
                </ul>                        
            </div>
            <div class="float"> 
            </div>
        </div>       
        <h1> <?php
			if ($sameperson) //Falls Betrachter gleich der Profilinhaber ist
				echo "Your shared recipes";
			else
				echo "$user->username's shared recipes";
 ?> </h1>
        <?php 	if ($sharecount->c > 0): ?>
		
        <!-- Geteilte Rezepte anzeigen -->

        <div class="dreieraufteilung share">
		<?php
			$counter = 1;
			
			//Algorithmus, damit die divs die richtige Class bekommen

			foreach ($shared as $recipe) {
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
    <?php
		else: echo "<h2 class='center error'> There are currently no shared recipes. </h2>";
		endif;
 ?>   
        <!-- Gespeicherte Rezepte anzeigen -->

        <h1> <?php
			if ($sameperson)
				echo "Your saved recipes";
			else
				echo "$user->username's saved recipes";
 ?> </h1> 
        
         <?php 	if ($favecount->c > 0): ?>

        <div class="dreieraufteilung fave">
		<?php
			$counter = 1;
			foreach ($faves as $recipe) {
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
    <?php
			else: echo "<h2 class='center error'> There are currently no saved recipes. </h2>";
			endif;
			endif;
	?> 
</div>

<?php

include "footer.php";
?>