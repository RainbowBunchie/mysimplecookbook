<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<?php

// wenn das Formular bereits abgeschickt wurde und ein Fehler festgestellt wurde, dann werden die bereits eingegebenen Werte wieder in die Input Felder geschrieben und nicht einfach gelöscht

if (isset($_POST['submit'])) {
	$title = $_POST['title'];
	$category = $_POST['category'];
	$difficulty = $_POST['difficulty'];
	$preptime = $_POST['preptime'];
	$people = $_POST['people'];
	$ingridients = $_POST['ingridients'];
	$instruction = $_POST['instructions'];
	if (isset($_POST['lactosefree']))
		$lactosefree = $_POST['lactosefree'];
	else
		$lactosefree = false;
	if (isset($_POST['glutenfree']))
		$glutenfree = $_POST['glutenfree'];
	else
		$glutenfree = false;
	if (isset($_POST['vegan']))
		$vegan = $_POST['vegan'];
	else
		$vegan = false;
	if (isset($_POST['vegeterian']))
		$vegeterian = $_POST['vegeterian'];
	else
		$vegeterian = false;

} 
//wenn ein neues Rezept erstellt wird 

else if (!$update) {
	$title = "";
	$category = "appetizer";
	$difficulty = "easy";
	$preptime = 10;
	$people = 2;
	$ingridients = "";
	$instruction = "";
	$lactosefree = false;
	$glutenfree = false;
	$vegeterian = false;
	$vegan = false;
} 
//wenn das Rezept bearbeitet wird, dann werden die früher eingegebenen Werte schon ins Formular geschrieben

else {
	$title = $recipe -> title;
	$category = $recipe -> category;
	$difficulty = $recipe -> difficulty;
	$preptime = $recipe -> preptime;
	$people = $recipe -> people;
	$ingridients = $recipe -> ingridients;
	$instruction = $recipe -> instructions;
	$lactosefree = $recipe -> lactosefree;
	$glutenfree = $recipe -> glutenfree;
	$vegeterian = $recipe -> vegetarian;
	$vegan = $recipe -> vegan;
} ?>

<div class="croparea"> 
<?php
	if (file_exists("imgrecipe/tmp.jpg")){
			unlink("imgrecipe/tmp.jpg");
			}
	include "crop-r.php";
?>
 <p>
		select a jpg-file with max size of 1Mb.
	</p>
</div>

<div class="formular">
	<form class="recipe" method="POST" enctype="multipart/form-data">
		<h2> name your dish: </h2>
		<input class="input" type="text"  maxlength="30" name="title" placeholder="Maximum length: 30" value="<?php echo $title ?>" required>
		<br>
		<h2> select a category for your dish: </h2>
		<input type='radio' name='category' value="appetizer" <?php
			if ($category == "appetizer")
				echo "checked";
 ?> >
		appetizer
		<input type='radio' name='category' value="maindish" <?php if($category == "maindish") echo "checked" ?>>
		main dish
		<input type='radio' name='category' value="dessert" <?php if($category == "dessert") echo "checked" ?>>
		dessert
		<input type='radio' name='category' value="drink" <?php if($category == "drink") echo "checked" ?>>
		drink <br>
		<h2> select a difficulty for your dish: </h2>
		<input type='radio' name='difficulty' value="easy" <?php if ($difficulty == "easy") echo "checked" ?> >
		easy
		<input type='radio' name='difficulty' value="medium" <?php if($difficulty == "medium") echo "checked" ?>>
		medium
		<input type='radio' name='difficulty' value="hard" <?php if($difficulty == "hard") echo "checked" ?>>
		hard <br>
		<h2> preparationstime in minutes:
		<input class="numberinput" type="number" min="5" max="500" step="5" value="<?php echo $preptime ?>" name="preptime" required>
		</h2>
		<h2> recipe is ideal for
		<input class="numberinput" type="number" min="1" max="20" step="1" value="<?php echo $people ?>" name="people" required>
		people </h2>
		<h2> ingredients with measurements: </h2>
		<textarea class="input" type="text" name="ingridients"  required><?php echo $ingridients ?></textarea>		
        <br>
        <h2> instructions: </h2>
                <textarea class="input" type="text" name="instructions" required><?php echo $instruction ?></textarea>
        <br>
        <h2> additional information: </h2>
        <input type="checkbox" name="lactosefree" <?php
		if ($lactosefree)
			echo "checked"; ?> >
		lactosefree
		<input type="checkbox" name="glutenfree" <?php
			if ($glutenfree)
				echo "checked"; ?> >
		glutenfree <br />
		<input type="checkbox" name="vegan" <?php
			if ($vegan)
				echo "checked"; ?>>
		vegan
		<input type="checkbox" name="vegeterian"<?php
			if ($vegeterian)
				echo "checked"; ?>>
		vegetarien <br />
		<input class="button submit" type="submit" value="<?php
			if (!$update) { echo "share";
			} else {echo "update";
			} 
			?>" name="submit" >
	</form>
</div>
