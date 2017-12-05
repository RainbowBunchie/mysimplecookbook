<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php

   	$pagetitle = "Share your recipe";

    include "header.php";
	
	$update = false; 
	
	$sth = $dbh->prepare(
      "INSERT INTO recipes
        (title, category, difficulty, preptime, people, ingridients, instructions, lactosefree, glutenfree, vegan, vegetarian, shared_by, time)
          VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
		
	//Überprüft alle Eingaben auf die Richtigkeit
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
			$error[2] = "Amount of people must be greater than 0";
			}
			
		$ingridients = strip_tags($_POST['ingridients']);
		if(($ingridients == " ")||(!$ingridients) || ($ingridients == "") ){
			$error[3] = "There are no ingridients!";
		}
		if((strlen ( $ingridients ) < 20 )){
			$error[4] = "Text in ingridients has to be greater than 20 letters";
		}
		
		
		$instructions = strip_tags($_POST['instructions']);
		if(($instructions == " ")||(!$instructions)){
			$error[5] = "There are no instructions!";
		}
		if((strlen ( $instructions ) < 20 )){
			$error[6] = "Text in instructions has to be greater than 20 letters";
		}
		
		
		if (isset( $_POST['lactosefree'])) $lactosefree  = 1;
			else $lactosefree  = 0;

		if (isset( $_POST['glutenfree'])) $glutenfree  = 1;
			else $glutenfree  = 0;

		if (isset( $_POST['vegan'])) $vegan  = 1;
					else $vegan  = 0;			
		
		if (isset( $_POST['vegeterian'])) $vegeterian  = 1;
			else $vegeterian  = 0;
			
		if (!file_exists("imgrecipe/tmp.jpg")){
			$error[9] = "Please select a photo ";
			}
		/*else if (file_exists("imgrecipe/tmp.jpg")){
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
			echo "<h2 class = 'error center'>Something went wrong:</h2> <ul class='center error'>";
			foreach ($error as $error_message) {
				echo "<li>$error_message</li><br>";
			}
			echo "</ul>";
		}
		//Falls kein Error ausgegeben wird: 
		else{
			$sth->execute(array($title, $category, $difficulty, $preptime, $people, $ingridients, $instructions, $lactosefree, $glutenfree, $vegan, $vegeterian, $_SESSION['ID'] ));
			$id = $dbh->lastInsertId('recipes_id_seq');
			
			if (file_exists("imgrecipe/tmp.jpg")){
			/*$filename = dirname( $_SERVER["SCRIPT_FILENAME"] ) . "/imgrecipe/" . $id . ".jpg";
			if (file_exists($filename)) {
					unlink($filename);
				} 
				$uploaddir = dirname( $_SERVER["SCRIPT_FILENAME"] ) . "/imgrecipe/";	
				move_uploaded_file($_FILES['bild']['tmp_name'], ($uploaddir . $id . $ext));*/
				rename ("imgrecipe/tmp.jpg", "imgrecipe/".$id.".jpg");

			}	
			header("Location: recipe.php?id=$id");           
		}
	}		     	    
    
	if (!isset($_SESSION['loggedin'])):	
        
?>
</ br>
<h2 class="center error"> You must be logged in to share a recipe! </h2>
<?php
else:
?>

<div class="container">
	<h1> Share your recipe </h1>

	<?php
	include "form_recipe.php";

	endif;
	?>

</div>

<?php
include "footer.php";
?>