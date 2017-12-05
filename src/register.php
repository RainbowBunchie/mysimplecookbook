<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php

$pagetitle = "Sign in";

include "header.php";
$update = false; //wird benötigt für das Formular -> siehe form.php

$sth = $dbh -> prepare("INSERT INTO users (firstname, lastname, email, username, isfemale, password) VALUES (?, ?, ?, ?, ?, ?)");

//Eingaben auf Richtigkeit prüfen:

if (isset($_POST['submit'])) {
	$fname = strip_tags($_POST['firstname']);
	if (($fname == " ") || (!$fname)) {
		$error[0] = "First name is empty!";
	}
	$sname = strip_tags($_POST['lastname']);
	if (($sname == " ") || (!$sname)) {
		$error[1] = "Last name is empty!";
	}
	$mail = htmlspecialchars(strip_tags($_POST['email']));
	if (($mail == " ") || (!$mail) || (strpos($mail, "@") == false)) {
		$error[2] = "Wrong e-mail input!";
	}
	$emailcheck = $dbh -> prepare("SELECT email FROM users WHERE email='$mail';");
	$emailcheck->execute();
	$person = $emailcheck -> fetch();
	if ($person != "") {
		$error[3] = "This e-mail adress is already taken!";
	}

	$username = strip_tags($_POST['username']);
	if (($username == " ") || (!$username)) {
		$error[4] = "Username is empty!";
	}

	$usernamecheck = $dbh -> prepare("SELECT username FROM users WHERE username='$username';");
	$usernamecheck->execute();
	$person = $usernamecheck -> fetch();
	if ($person != "") {
		$error[5] = "This username is already taken!";
	}

	$isfemale = $_POST['isfemale'];

	if ($_POST['password'] != $_POST['password2']) {
		$error[6] = "The repeated password has to be the same as the password above!";
	} else {
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	}
	if ($_FILES['bild']['name'] != null && $_FILES['bild']['name'] != "") {
		$filename = basename($_FILES['bild']['name']);
		$ext = substr($filename, -4);

		if ($ext != '.jpg') {
			$error[7] = "Only jpg is allowed to be uploaded!";
		}
		if ($_FILES['bild']['size'] > 2000000) {
			$error[8] = "File is too big to be uploaded. (Max size is 2Mb)";
		}
	}

	if (isset($error)) {
		echo "<h2 class = 'error center'>Something went wrong:</h2><ul class='error center'>";
		foreach ($error as $error_message) {
			echo "<li>$error_message</li><br>";
		}
		echo "</ul>";
	} else {
		$sth -> execute(array($fname, $sname, $mail, $username, $isfemale, $password));
		$id = $dbh -> lastInsertId('users_id_seq');
		if (file_exists("imgprofile/tmp.jpg")) { //Falls ein Bild ausgewählt wurde
			/*$filename = dirname($_SERVER["SCRIPT_FILENAME"]) . "/imgprofile/" . $id . ".jpg";
			$uploaddir = dirname($_SERVER["SCRIPT_FILENAME"]) . "/imgprofile/";
			move_uploaded_file($_FILES['bild']['tmp_name'], ($uploaddir . $id . ".jpg"));*/	
			rename ("imgprofile/tmp.jpg", "imgprofile/".$id.".jpg");
		}
		header("Location: login.php");
	}
}
?>
<div class="container">
	<h1> Sign In </h1>

	<?php
	include "form.php";
	?>
</div>

<?php
include "footer.php";
?>