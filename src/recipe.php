<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->

 <?php
$id = $_GET['id'];

$pagetitle = $recipe -> title;

include "functions.php";

$recipehelp = $dbh -> prepare("SELECT * FROM recipes WHERE id = '$id';");
$recipehelp->execute();
$recipe = $recipehelp->fetch();
$commentshelp = $dbh -> prepare("SELECT * FROM comments WHERE id_recipe = '$recipe->id' ORDER BY time desc;");
$commentshelp->execute();
$comments = $commentshelp->fetchAll();
$userhelp = $dbh -> prepare("SELECT id, username, isfemale FROM users WHERE id = '$recipe->shared_by';");
$userhelp->execute();
$user = $userhelp->fetch();
$ratingcounthelp = $dbh -> prepare("SELECT count(*) c FROM ratings WHERE id_recipe = $id;");
$ratingcounthelp->execute();
$ratingcount = $ratingcounthelp->fetch();

$insertcomment = $dbh -> prepare("INSERT INTO comments (id_recipe, id_user, title, comment, time) VALUES (?, ?, ?, ?, NOW())");
$addtofavorites = $dbh -> prepare("INSERT INTO favorites (id_recipe, id_user) VALUES ($id, ?)");
$ratinginsert = $dbh -> prepare("INSERT INTO ratings (id_user, id_recipe, rating) VALUES (?, $id, ?)");

$deletecomment = $dbh -> prepare("DELETE FROM comments where id_recipe=$id and id_user = ? and time= ? ;");

include "header.php";

// Hier wird das Kommentarfeld geprüft und das Kommentar wenn alles passt in die DB gespeichert

if (isset($_POST['submit'])) {
	$title = strip_tags($_POST['title']);
	if (($title == " ") || (!$title)) {
		$error[0] = "Title is empty!";
	}
	$comment = strip_tags($_POST['text']);
	if (($comment == " ") || (!$comment) || ($comment == "")) {
		$error[1] = "There are no ingridients!";
	}
	if ((strlen($comment) < 20)) {
		$error[2] = "Text in comment has to be greater than 20 letters";
	}

	if (isset($error)) {
		echo "<h2 class = 'error center'>Something went wrong:</h2><ul class='center'>";
		foreach ($error as $error_message) {
			echo "<li>$error_message</li><br>";
		}
		echo "</ul>";
	} else {
		$insertcomment -> execute(array($recipe -> id, $_SESSION['ID'], $title, $comment));
		//header("Location: recipe.php?id=$id");
	}
}
//Falls bereits ein Rating vom eingeloggten User abgegeben wurde

if (isset($_SESSION['loggedin'])) {
	$iduser = $_SESSION['ID'];
	$ratingexisthelp = $dbh -> prepare("SELECT rating FROM ratings WHERE id_user = $iduser and id_recipe = $id;");
	$ratingexisthelp->execute();
	$ratingexist = $ratingexisthelp->fetch();
}

// Hier wird das Rating in die DB gespeichert, wenn alles passt

if (isset($_POST['submitrating'])) {

	$ratingupdate = $dbh -> prepare("UPDATE ratings set rating = ? where id_recipe=$id and id_user = $iduser;");
	$ratingall = $dbh -> prepare("UPDATE recipes set rating = ? where id = $id");

	$rating = $_POST['rating'];

	if ($ratingexist == null && $rating < 6 && $rating > 0) {
		$ratinginsert -> execute(array($iduser, $rating));
		$ratingall -> execute(array(($recipe -> rating + $rating)));
	} else if ($rating < 6 && $rating > 0) {
		$newrating = ($rating + $recipe -> rating - $ratingexist -> rating);
		$ratingall -> execute(array($newrating));
		$ratingupdate -> execute(array($rating));
		header("Location: recipe.php?id=$id");
	}
}
?>

<script>
	
	$(document).ready(function() {
		//hier wird geprüft wie hoch der Text in den instructions ist, und je nachdem wird die div drumherum auf die richtige Größe angepasst, dies ist für die Objekte, die sich darunterbefinden wichtig
		var width = $(window).width();
		
		var heightdiv = $('.middle').height();

		if (width > 1272) {
			$('.dreieraufteilung').css('height', (heightdiv+20));

		} else if (width > 750) {
			$('.recipe .dreieraufteilung').css('height', (heightdiv + 20));
		}
	}); 
	
	//Hier wird der Value, den der Schieberegler (des Ratingfelds) hat direkt beim Verschieben angezeigt im darüber platzierten Feld

	function updateTextInput(val) {
		document.getElementById('textInput').value = val;
	}
</script>

<div class="container recipe">
	<h1> <?php echo $recipe -> title;
	//Falls der User der Ersteller des Rezepts ist
	if (isset($_SESSION['USER']) && $_SESSION['USER'] == $user -> username)
		echo " <a class='edit' href='edit-recipe.php?id=$recipe->id'>(edit) </a> ";
?>
	</h1>
	<div class="information">
		<p class = 'center'>
			<b> Shared by: </b><?php echo "<a href='profile.php?id=$user->id'>  $user->username </a> "; ?>
		</p>
		<p class = 'center'>
			<b>Difficulty:</b> <?php echo $recipe -> difficulty; ?>
		</p>
		<p class = 'center'>
			<b> Preperation Time: </b> <?php echo $recipe -> preptime; ?> minutes
		</p>
		<?php
		if (($recipe -> lactosefree == 1) or ($recipe -> glutenfree == 1) or ($recipe -> vegan == 1) or ($recipe -> vegetarian == 1)) {
			echo "<p class='center'> <b> Additional Information: </b> </p> <p class='center'>";
			if ($recipe -> lactosefree == 1)
				echo "lactosefree ";
			if ($recipe -> glutenfree == 1)
				echo "glutenfree ";
			if ($recipe -> vegan == 1)
				echo "vegan ";
			if ($recipe -> vegetarian == 1)
				echo "vegetarian";
			echo "</p>";
		}
		?>
		<p class="center">
			<b> Rating: </b> <?php
			if ($ratingcount -> c > 0)
				echo(round($recipe -> rating / $ratingcount -> c)); //Hier wird das Rating erst berechnet 
			else
				echo "0"; ?>/5 (
<?php echo $ratingcount -> c;
	if ($ratingcount -> c < 2)
		echo " rating";
	else
		echo " ratings";
			?>
         )
		</p>
		<?php if(isset($_SESSION['loggedin'])): //eigenes Rating anzeigen und ändern lassen, wenn ein User eingeloggt ist
		?>
		<p class="center">
			<b> Your rating: </b>
		</p>
		<div class="center rating">
			<form class="formular" method="POST">
				<input type="text" id="textInput" value="<?php if ($ratingexist!= null) echo $ratingexist->rating; else echo"0" ?>">
				<br />
				<input class="center" type="range" name="rating" min="1" max="5" value="<?php if ($ratingexist!= null) echo $ratingexist->rating; else echo"1" ?>" oninput="updateTextInput(this.value);">
				<br />
				<input class="button" type="submit" value="Confirm rating"  name="submitrating"/>
			</form>
            
			<?php
			endif;
			?>
        <!-- Code von Developertools https://developers.facebook.com/docs/plugins/share-button -->

            <div class="fb-share-button" data-href="https://users.multimediatechnology.at/~fhs39837/mmp1/recipe.php?id=<?php echo $id; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fusers.multimediatechnology.at%2F%7Efhs39837%2Fmmp1%2Frecipe.php%3Fid%3D<?php echo $id; ?>&amp;src=sdkpreparse">Teilen</a></div>   
            
         <!-- Ende Code Developertools --> 
             
		</div>
		<div class="border"></div>
		<div class="dreieraufteilung">
			<div class = "image left">
				<?php echo "<img src='imgrecipe/" . $id . ".jpg' title='" . $recipe -> title . "' alt='dish'>"; ?>
			</div>
			<div class = "middle" >
				<h2> Instructions </h2>
				<?php echo $recipe->instructions ?>
			</div>
			<div class = "right" >
				<h2> ingridients for <?php echo " <span class='green' > $recipe->people </span>";
				if ($recipe -> people == 1)
					echo "person";
				else
					echo "people";
				?></h2>
				<?php echo $recipe->ingridients ?>
			</div>
		</div>
		<?php //Hier ist der Code für den Favoritenbutton
		if (isset($_SESSION['ID'])){
			$iduserloggedin = $_SESSION['ID'];
			$alreadyinfavoriteshelp = $dbh->prepare( "SELECT count(*) c FROM favorites WHERE id_user = $iduserloggedin and id_recipe = $recipe->id;");
			$alreadyinfavoriteshelp->execute();
			$alreadyinfavorites = $alreadyinfavoriteshelp->fetch();
		}
		//Button nicht anzeigen, wenn man selber ersteller ist, weil das Rezept eh in einer Sammlung gespeichert ist
		if(isset($_SESSION['loggedin']) and $_SESSION['ID'] != $recipe->shared_by):
		?>

		<!-- Hier wird der Button, zum Reszeptspeichern angelegt -->

		<div class="saverecipe">
			<form method="POST">
				<?php if ($alreadyinfavorites->c == 0 ): ?>
				<input class="button" type ="submit" name="saverecipe" value="Save recipe" />
				<?php else: ?>
				<input class="buttonred" type ="submit" name="deletefromfavorites" value="delete from favorites" />
				<?php endif; ?>
			</form>
		</div>

		<?php
		endif;

		if (isset($_POST['saverecipe'])){ //Wenn Rezept gespeichert werden soll
			$addtofavorites->execute(array($_SESSION['ID']));
			header("Location: recipe.php?id=$id");
		}

		if (isset($_POST['deletefromfavorites'])){ //Rezept wieder von den Favoriten entfernen
			echo $id;
			echo $iduserloggedin;
			$help = $dbh->prepare("DELETE FROM favorites WHERE id_recipe=$id AND id_user = $iduserloggedin;");
			$help->execute();
			header("Location: recipe.php?id=$id");
		}
		?>

		<h1> Comments </h1>

		<?php
		foreach ($comments as $comment) { //Gibt jedes Kommentar aus, das vorhanden ist

			$getUsername = $dbh -> query("SELECT username, id FROM users WHERE id = $comment->id_user") -> fetch();

			$title = $comment -> title;
			$commenttext = $comment -> comment;

			if (isset($_SESSION['loggedin']) and $_SESSION['ID'] == $getUsername -> id)
				$span = "<form method='POST' class='deletecomment'> <span class='x'> <input type='submit' class='button' value='✘' name='sub'> </form>";
			else
				$span = "";

			echo "<div class='comments'> <div class='commenttop'> <p class='center'> <a class='white commentdelete' href='profile.php?id=$getUsername->id'> $getUsername->username</a> " . "wrote: " . " </p>" . $span . "</div><h2 class='center'> $title </h2> <div class='commentext center'> $commenttext </div> </div>";
		}
		if ($comments == null) {
			echo "<div class='center'> There are no comments for this recipe so far. </div>";
		}
		if (isset($_POST['sub'])) {
			$deletecomment -> execute(array($comment -> id_user, $comment -> time));
		}
		?>
		<h1> write a comment </h1>
		<!-- Kommentarschreiben nur erlauben, wenn der User eingeloggt ist --> 
		<?php if (!isset($_SESSION['loggedin'])): echo "<p class='center'> You have to be logged in to write a comment </p>"; else:
		?>
		<div class="formular" >
			<form method="POST">
				<h2> Give your comment a title</h2>
				<input class="input" type="text" name="title">
				<br />
				<h2>Share your opinion</h2>
				<textarea class="input" type="text" name="text" placeholder="Tell other how the dish turned out and what you did different to improve the taste even more!" required></textarea>
				<input type="submit" value="submit" name="submit" class="button">
			</form>
		</div>

		<?php
		endif;
		?>
	</div>
</div>

<?php
include "footer.php";
?>