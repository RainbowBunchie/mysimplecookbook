<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<?php

// wenn das Formular bereits abgeschickt wurde und ein Fehler festgestellt wurde, dann werden die bereits eingegebenen Werte wieder in die Input Felder geschrieben und nicht einfach gelöscht
if (isset($_POST['submit'])) {
	$fname = $_POST['firstname'];
	$lname = $_POST['lastname'];
	$mail = $_POST['email'];
	$isfemale = $_POST['isfemale'];
	$username = $_POST['username'];
} 
//bei einer Regestrierung 
else if (!$update) {
	$fname = "";
	$lname = "";
	$mail = "";
	$isfemale = true;
	$username = "";
} 
//wenn das Profil bearbeitet wird, dann werden die früher eingegebenen Werte schon ins Formular geschrieben
else {
	$fname = $user -> firstname;
	$lname = $user -> lastname;
	$mail = $user -> email;
	$isfemale = $user -> isfemale;
	$username = $user -> username;
}
//wenn das derzeitige Profilbild gelöscht werden soll
if (isset($_POST['deletepicture'])) {
	unlink("imgprofile/" . $id . ".jpg");
	header("Location: edit-profile.php?id=$id");
}

?>
<div class="croparea"> 		
	
	<?php 
	if (file_exists("imgprofile/tmp.jpg")){
		unlink("imgprofile/tmp.jpg");
		}
		include "crop.php";
	?>
    <p>
		select a jpg-file with max size of 1Mb.
	</p>
</div>
<div class="formular" >


	<form class="recipe" method="POST" enctype="multipart/form-data">
			<?php
         if (file_exists("imgprofile/" . $id . ".jpg")) { // falls bereits ein Profilbild hochgeladen wurde -> bietet Möglichkeit dieses zu anzusehen und zu löschen
echo " <br><input class='buttonred' type = 'submit' name='deletepicture' value='Delete picture' />";
}
?>
		<h2> first name: </h2>
		<input class="input" type="text"  maxlength="30" name="firstname" value=<?php echo $fname ?> >
		<br />
		<h2> last name: </h2>
		<input class="input" type="text"  maxlength="30" name="lastname" value=<?php echo $lname ?> >
		<br />
		<h2> e-Mail adress: </h2>
		<input class="input" type="email"  maxlength="30" name="email" value=<?php echo $mail ?> >
		<br />
		<h2> gender </h2>
		<input type='radio' name='isfemale' value="false" <?php if(!$isfemale) echo "checked" ?> >
		male
		<input type='radio' name='isfemale' value="true" <?php if($isfemale) echo "checked" ?>>
		female
		<br />

		<h2> username: </h2>
		<input class="input" type="text"  maxlength="30" name="username" value=<?php echo $username ?> >
		<br />
		<?php if (!$update):
		?>
		<h2> password: </h2>
		<input class="input" type="password"  maxlength="30" name="password">
		<br>
		<h2> repeat password: </h2>
		<input class="input" type="password"  maxlength="30" name="password2">
		<br>
		<?php endif; ?>
		<input class="button" type="submit" value= <?php
			if (!$update) { echo "register";
			} else {echo "update";
			}
		 ?>  name="submit" >
	</form>
</div>