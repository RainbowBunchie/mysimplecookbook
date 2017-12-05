<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php

$pagetitle = "Login";

include "header.php";

//Prüfen ob die Eingegebenen Daten mit einem angelegten User übereinstimmen
if (isset($_POST['submit'])) { 
	$username = $_POST['username'];
	$password = $_POST['password'];
	$usernamecheck = $dbh -> prepare("SELECT id, password FROM users WHERE username='$username';");
	$usernamecheck->execute();
	$user = $usernamecheck -> fetch();
	
	//Falls die Eingaben stimmen: 
	if ($user != "" and password_verify($password, $user -> password)) {
		$_SESSION['USER'] = $username;
		$_SESSION['loggedin'] = true;
		$_SESSION['ID'] = $user -> id;
		header("Location: index.php");
		exit ;
	} else {
		echo "<h2 class='center error'> Username or Passwort incorrect </h2>";
	}
}
?>
<div class="container">
	<h1> Login </h1>

	<div class ="formular" >
		<form action="" method="POST">
			<h2> username: </h2>
			<input class="input" type="text"  maxlength="30" name="username">
			<br />
			<h2> password: </h2>
			<input class="input" type="password"  maxlength="30" name="password">
			<br>
			<input class="button" type="submit" value="login"  name="submit"  >
		</form>
		<p>
			Not registered yet? Register <a href="register.php" >here</a>!
		</p>
	</div>
</div>

<?php

include "footer.php";
?>
