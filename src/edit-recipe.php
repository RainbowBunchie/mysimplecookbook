<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<?php	
   	$pagetitle = "Edit your recipe";
		
	$update = true; //wichtig für das Formular! -> siehe in form_recipe.php

    include "header.php";
	
	$id = $_GET['id'];	//Rezeptid speichern -> für queries wichtig

	$recipehelp = $dbh->prepare( "SELECT * FROM recipes WHERE id = '$id';");
	$recipehelp->execute();	
	$recipe = $recipehelp->fetch();
	
	$updaterecipe = $dbh->prepare("UPDATE recipes set title = ?, category =?, difficulty=?, preptime=?, people=?, ingridients=?, instructions=?, lactosefree=?, glutenfree=?, vegan=?, vegetarian=? where id=$id;");
	
	$deleterecipe = $dbh->prepare("DELETE FROM recipes where id=$id;");
	$deletecomments =  $dbh->prepare("DELETE FROM comments where id_recipe=$id;");
	$deletefav = $dbh->prepare("DELETE FROM favorites where id_recipe=$id;");
	$deleteratings = $dbh->prepare("DELETE FROM ratings where id_recipe=$id;");
	
	$fehler = false;
	
	//Fehlermeldung, falls der User nicht berechtigt ist das Rezept zu editieren 
	
 	if (!isset($_SESSION['loggedin'])){ 
		echo  "<h2 class='center loggedin'> You must be logged in to edit your recipes </h2>"; 
		$fehler = true;
	}
    else if ($_SESSION['ID'] != $recipe->shared_by){
		echo "<h2 class='center loggedin'> You are not allowed to edit other people's recipes </h2>";
		$fehler = true;
	}
	
	if(!$fehler): 
	
	if (isset ($_POST['deleterecipe'])){ //wenn auf den Delete Button gedrückt wird
		
		$commentcounthelp = $dbh->prepare( "SELECT count(*) AS c FROM comments WHERE id_recipe = '$id';");
		$commentcounthelp->execute();
		$commentcount = $commentcounthelp->fetch();
		$favecounthelp = $dbh->prepare( "SELECT count(*) AS c FROM favorites WHERE id_recipe = '$id';");
		$favecounthelp->execute();
		$favecount = $favecounthelp->fetch();
		$ratingcounthelp = $dbh->prepare("SELECT count(*) c FROM ratings WHERE id_recipe = $id;");
		$ratingcounthelp->execute;
		$ratingcount = $ratingcounthelp->fetch();
		
		unlink( "imgrecipe/" . $id . ".jpg"); 
		if ($favecount->c > 0) $deletefav->execute();
		if ($commentcount->c > 0 ) $deletecomments->execute();
		if ($ratingcount->c > 0) $deleteratings->execute();
		$deleterecipe->execute();
		header("Location: index.php");             
	}
	
	//Wenn das Rezept geupdated wird, werden die Angaben auf Richtigkeit geprüft
	if (isset ($_POST['submit'])){		
		$title = strip_tags($_POST['title']);
		if (($title == " ")||(!$title)) {
			$error[0] = "Title is empty!";
		}
		
		$difficulty = $_POST['difficulty'];

		$category = $_POST['category'];
					
		$preptime = $_POST['preptime'];
		if($preptime <= 0 ){
			$error[1] = "Preperation time must be greater than 0!";
			}
		
		$people = $_POST['people'];
		if($people <= 0 ){
			$error[2] = "Amount of people must be greater than 0!";
			}
			
		$ingridients = strip_tags($_POST['ingridients']);
		if(($ingridients == " ")||(!$ingridients) || ($ingridients == "") ){
			$error[3] = "There are no ingredients!";
		}
		if((strlen ( $ingridients ) < 20 )){
			$error[4] = "Text in ingredients has to be greater than 20 letters!";
		}	
		
		$instructions = strip_tags($_POST['instructions']);
		if(($instructions == " ")||(!$instructions)){
			$error[5] = "There are no instructions!";
		}
		if((strlen ( $instructions ) < 20 )){
			$error[6] = "Text in instructions has to be greater than 20 letters!";
		}		
		
		if (isset( $_POST['lactosefree'])) $lactosefree  = 1;
			else $lactosefree  = 0;

		if (isset( $_POST['glutenfree'])) $glutenfree  = 1;
			else $glutenfree  = 0;

		if (isset( $_POST['vegan'])) $vegan  = 1;
					else $vegan  = 0;			
		
		if (isset( $_POST['vegeterian'])) $vegeterian  = 1;
			else $vegeterian  = 0;
				
		/*if ($_FILES['bild']['name'] != null && $_FILES['bild']['name'] != ""){
			$filename = basename($_FILES['bild']['name']);
			$ext = substr($filename, -4);
			 
			if( $ext != '.jpg' ) {
			   $error[7] = "Only jpg is allowed to be uploaded!";
			}
			if ($_FILES['bild']['size'] > 2000000){
				$error[8] = "File is too big to be uploaded. (Max size is 2Mb)";
				}
		}*/
		if(isset ($error)) {
			echo "<h2 class = 'center error'> <b> Something went wrong: </b> </h2> <ul class='center'>";
			foreach ($error as $error_message) {
				echo "<li>$error_message</li><br>";
			}
			echo "</ul>";
		}
		//Falls alle Eingaben richtig sind
		else{		 
			$updaterecipe->execute(array($title, $category, $difficulty, $preptime, $people, $ingridients, $instructions, $lactosefree, $glutenfree, $vegan, $vegeterian));
			if (file_exists("imgrecipe/tmp.jpg")) {
					if (file_exists("imgrecipe/".$id.".jpg")) {
						unlink($filename); //falls schon ein Bild vorhanden und ein neues gespeichert wird, dann dieses zuerst löschen
					}				
					rename ("imgrecipe/tmp.jpg", "imgrecipe/".$id.".jpg");
				} 
			/*if ($_FILES['bild']['name'] != null && $_FILES['bild']['name'] != ""){
			$filename = dirname( $_SERVER["SCRIPT_FILENAME"] ) . "/imgrecipe/" . $id . ".jpg";
			if (file_exists($filename)) {
				unlink($filename); //Falls schon ein Bild vorhanden war, wird dieses zuerst gelöscht, dann ein neues gespeichert
			} 
			$uploaddir = dirname( $_SERVER["SCRIPT_FILENAME"] ) . "/imgrecipe/";	
			move_uploaded_file($_FILES['bild']['tmp_name'], ($uploaddir . $id . $ext));
			}	*/
			header("Location: recipe.php?id=$id");             
		}
	}
?>

<div class="container">
<h1> Edit your recipe </h1>
<?php
include "form_recipe.php";
?>
<h1> Delete your recipe </h1>
<p class="center"> This process will delete your recipe forever. You can not restore it later on. </p>
<div class="formular">
    <form method="POST">
        <input class="buttonred" type = "submit" name="deleterecipe" value="Delete your recipe" />
    </form>
</div>
<?php
echo "</div>";
endif;
include "footer.php";
?>